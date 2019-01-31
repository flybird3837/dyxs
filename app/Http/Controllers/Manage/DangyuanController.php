<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Traits\CleanCache;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dangyuan;
use App\Models\DangyuanImport;
use App\Http\Controllers\Traits\RoleHelper;
use App\Http\Controllers\Traits\QiniuHelper;
use Maatwebsite\Excel\Facades\Excel;
use zgldh\QiniuStorage\QiniuStorage;

class DangyuanController extends Controller
{
    use RoleHelper, CleanCache, QiniuHelper;

    private $startRow = 2;
    private $nameCol = 'A';
    private $sexCol = 'B';
    private $inTimeCol = 'C';

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Dangyuan::query();
        $pageMap = [];

        if ($uuid = $request->uuid) {
            $query->where('uuid', $uuid);
            $pageMap['uuid'] = $uuid;
        }

        if ($name = $request->name) {
            $query->where('name', 'like', "%$name%");
            $pageMap['name'] = $name;
        }
        $dangyuans = $query->orderBy('created_at', 'DESC')
            ->where('project_id', $this->getUserProject()->id)
            ->paginate(request('per_page', 15));

        $disk = QiniuStorage::disk('qiniu');
        $files = $disk->files('org/4/dangyuan/1/');
        print_r($files);
        return view('manage.dangyuan.index', compact('dangyuans', 'pageMap'));
    }

    /**
     * 更新
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request){
        $project_id = $this->getUserProjectId();
        if (strtotime($request->in_time) === false)
            return 1;
        if ($request->sex != '男' && $request->sex != '女')
            return 2;
        $dangyuan = Dangyuan::find($request->id);
        $dangyuan->name = $request->name;
        $dangyuan->sex = $request->sex == '男' ? 1 : 0;
        $dangyuan->in_time = $request->in_time;
        $dangyuan->save();
        return 0;
    }

    /**
     * 导入
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */
    public function upload(Request $request)
    {
        try{
            $project_id = $this->getUserProjectId();
            $list = $this->getUploadData($request);
            foreach ($list as $data) {
                $exists = Dangyuan::where('project_id', $project_id)
                                  ->where('name', $data['name'])
                                  ->where('sex', $data['sex'])
                                  ->where('in_time', $data['in_time'])
                                  ->exists();
                if (!$exists){
                    $dangyuan = new Dangyuan();
                    $dangyuan->project_id = $this->getUserProjectId();
                    $dangyuan->name = $data['name'];
                    $dangyuan->sex = $data['sex'];
                    $dangyuan->in_time = $data['in_time'];
                    $dangyuan->save();
                }
            }
            return 0;
        }catch(\Exception $e){
            return 1;
        }

    }

    /**
     * 导出
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */
    public function download(Request $request)
    {
        require_once(base_path() . '/app/Handlers/phpexcel/Classes/PHPExcel.php');
        $project_id = $this->getUserProjectId();
        $list = Dangyuan::where('project_id', $project_id)->get();
        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex(0);
        $PHPExcel->getActiveSheet()->setCellValue($this->nameCol.'1',"姓名");
        $PHPExcel->getActiveSheet()->setCellValue($this->sexCol.'1',"性别");
        $PHPExcel->getActiveSheet()->setCellValue($this->inTimeCol.'1',"入党时间");
        $i = 2;
        foreach ($list as $data) {
            $PHPExcel->getActiveSheet()->setCellValue($this->nameCol.$i, $data->name);
            $PHPExcel->getActiveSheet()->setCellValue($this->sexCol.$i, $data->sex == 1 ? '男' : '女');
            $PHPExcel->getActiveSheet()->setCellValue($this->inTimeCol.$i, $data->in_time);
            $i++;
        }
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.'dangyuan.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }   

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUploadData(Request $request)
    {
        require_once(base_path() . '/app/Handlers/phpexcel/Classes/PHPExcel.php');
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        $filePath = request()->file('file');
        if(!$PHPReader->canRead($filePath)){
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if(!$PHPReader->canRead($filePath)){
                echo 'no Excel';
                return ;
            }
        }
        //建立excel对象，此时你即可以通过excel对象读取文件，也可以通过它写入文件
        $PHPExcel = $PHPReader->load($filePath);

        /**读取excel文件中的第一个工作表*/
        $currentSheet = $PHPExcel->getSheet(0);
        /**取得最大的列号*/
        $allColumn = $currentSheet->getHighestColumn();
        /**取得一共有多少行*/
        $allRow = $currentSheet->getHighestRow();

        $list = array();
        for($rowIndex=$this->startRow;$rowIndex<=$allRow;$rowIndex++){
            for($colIndex='A';$colIndex<=$allColumn;$colIndex++){
                $addr = $colIndex.$rowIndex;
                $cell = $currentSheet->getCell($addr)->getValue();
                if($cell instanceof PHPExcel_RichText)     //富文本转换字符串
                    $cell = $cell->__toString();
                if ($colIndex == $this->nameCol)
                    $name = $cell;
                else if ($colIndex == $this->sexCol){
                    if ($cell == '男')
                        $sex = 1;
                    else
                        $sex = 0;
                }
                else if ($colIndex == $this->inTimeCol){
                    $n = intval(($cell - 25569) * 3600 * 24); //转换成1970年以来的秒数
                    $inTime = gmdate('Y-m-d',$n);//格式化时间,不是用date哦, 时区相差8小时的

                    if(isset($name) && isset($sex) && isset($inTime)){
                        array_push($list, ['name' => $name, 'sex' => $sex, 'in_time' => $inTime]);
                    }
                    $name = null;
                    $sex = null;
                    $in_time = null;
                }
            }
        }
        return $list;

    }

}
