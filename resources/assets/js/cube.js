import cax from 'cax'
const { Graphics, Bitmap, Text, Group } = cax
class Ball {
    constructor(x, y, No) {
        this.x = x
        this.y = y
        this.No = No
        this.width = 25
        this.height = 14
    }
}
class Loading extends Group {
    constructor() {
        super()
    }
    begin(){
        const rect = new cax.Rect(this.width, this.height, {
            fillStyle: '#f5f5f5'
        })

        this.g = new Graphics();
        this.text = new cax.Text('导览图 加载中..', {
            font: '40px Arial',
            color: '#ff7700',
            baseline: 'top'
        })
        this.add(rect)
        this.add(this.g)
        this.add(this.text)

        var arr = this.build(25, 14, this.width/2  ,this.height/2)
        this.text.x = this.width / 2 - this.text.getWidth() / 2
        this.text.y = this.height / 2 +100
        for (var i = 0, len = arr.length; i < len; i++) {
            this.drawBall(this.g, arr[i]);
        }
        this.loop(arr, this.g);
    }
    loop(arr, context) {
        this.moveRubic(arr, context, 'red');
        setInterval(()=> {
            this.moveRubic(arr, context, 'red');
        }, 4800);
        setTimeout(()=> {
            this.moveRubic(arr, context, 'orange')
            setInterval(()=> {
                this.moveRubic(arr, context, 'orange')
            }, 4800);
        }, 800)
        setTimeout(()=> {
            this.moveRubic(arr, context, 'blue')
            setInterval(()=> {
                this.moveRubic(arr, context, 'blue')
            }, 4800);
        }, 1600)
        setTimeout(()=> {
            this.moveRubic(arr, context, 'red', -1)
            setInterval(()=> {
                this.moveRubic(arr, context, 'red', -1)
            }, 4800);
        }, 2400)
        setTimeout(()=> {
            this.moveRubic(arr, context, 'orange', -1)
            setInterval(()=> {
                this.moveRubic(arr, context, 'orange', -1)
            }, 4800);
        }, 3200)
        setTimeout(()=> {
            this.moveRubic(arr, context, 'blue', -1)
            setInterval(()=> {
                this.moveRubic(arr, context, 'blue', -1)
            }, 4800);
        }, 4000)
    }
    moveRubic(arr, context, color, direct) {
        var width = arr[0].width;
        var count = width;
        var direct = direct || 1;
        var interval = setInterval(()=> {
            if (!count) {
                clearInterval(interval);
                return;
            }
            if (color === 'red') {
                for (var i = 0; i < 27; i++) {
                    var temp = arr[i];
                    if (temp.No < 10) {
                        temp.x -= 1 * direct;
                        temp.y += (1 / width) * temp.height * direct;
                    }
                    if (temp.No > 18) {
                        temp.x += 1 * direct;
                        temp.y -= (1 / width) * temp.height * direct;
                    }
                }
            } else if (color === 'orange') {
                for (var i = 0; i < 27; i++) {
                    var temp = arr[i];
                    if ((temp.No + 2) % 3 === 0) {
                        temp.x += 1 * direct;
                        temp.y += (1 / width) * temp.height * direct;
                    }
                    if (temp.No % 3 === 0) {
                        temp.x -= 1 * direct;
                        temp.y -= (1 / width) * temp.height * direct;
                    }
                }
            } else if (color === 'blue') {
                for (var i = 0; i < 27; i++) {
                    var temp = arr[i];
                    var judTop = false;
                    var judBottom = false;
                    temp.No == 1 ? judTop = true : temp.No == 2 ? judTop = true : temp.No == 3 ? judTop = true : temp.No == 10 ? judTop = true : temp.No == 11 ? judTop = true : temp.No == 12 ? judTop = true :
                        temp.No == 19 ? judTop = true : temp.No == 20 ? judTop = true : temp.No == 21 ? judTop = true : '';
                    temp.No == 7 ? judBottom = true : temp.No == 8 ? judBottom = true : temp.No == 9 ? judBottom = true : temp.No == 16 ? judBottom = true : temp.No == 17 ? judBottom = true : temp.No == 18 ? judBottom = true :
                        temp.No == 25 ? judBottom = true : temp.No == 26 ? judBottom = true : temp.No == 27 ? judBottom = true : '';
                    if (judTop) {
                        temp.y -= (2 / width) * temp.height * direct;
                    }
                    if (judBottom) {
                        temp.y += (2 / width) * temp.height * direct;
                    }
                }
            }
            context.clear()
            for (var i = 0, len = arr.length; i < len; i++) {
                this.drawBall(this.g, arr[i]);
            }
            count--;
        }, 10)
    }

    build(width, height, X, Y) {
        var arr = [];
        //floor
        arr.push(new Ball(X, Y - height * 4, 27));
        arr.push(new Ball(X - width, Y - height * 3, 18));
        arr.push(new Ball(X + width, Y - height * 3, 26));
        arr.push(new Ball(X - width * 2, Y - height * 2, 9));
        arr.push(new Ball(X + width * 2, Y - height * 2, 25));
        arr.push(new Ball(X, Y - height * 2, 17));
        arr.push(new Ball(X + width, Y - height, 16));
        arr.push(new Ball(X - width, Y - height, 8));
        arr.push(new Ball(X, Y, 7));
        //second
        arr.push(new Ball(X, Y - height * 6, 24));
        arr.push(new Ball(X - width, Y - height * 5, 15));
        arr.push(new Ball(X + width, Y - height * 5, 23));
        arr.push(new Ball(X - width * 2, Y - height * 4, 6));
        arr.push(new Ball(X + width * 2, Y - height * 4, 22));
        arr.push(new Ball(X, Y - height * 4, 14));
        arr.push(new Ball(X + width, Y - height * 3, 13));
        arr.push(new Ball(X - width, Y - height * 3, 5));
        arr.push(new Ball(X, Y - height * 2, 4));
        //third
        arr.push(new Ball(X, Y - height * 8, 21));
        arr.push(new Ball(X - width, Y - height * 7, 12));
        arr.push(new Ball(X + width, Y - height * 7, 20));
        arr.push(new Ball(X - width * 2, Y - height * 6, 3));
        arr.push(new Ball(X + width * 2, Y - height * 6, 19));
        arr.push(new Ball(X, Y - height * 6, 11));
        arr.push(new Ball(X + width, Y - height * 5, 10));
        arr.push(new Ball(X - width, Y - height * 5, 2));
        arr.push(new Ball(X, Y - height * 4, 1));
        return arr;
    }
    drawBall(context, ball) {
        //console.log(ball)
        context
            .beginPath()
            .fillStyle('#419099')
            .strokeStyle('#419099')
            .moveTo(ball.x, ball.y - ball.height * 2)
            .lineTo(ball.x - ball.width, ball.y - ball.height)
            .lineTo(ball.x, ball.y)
            .lineTo(ball.x + ball.width, ball.y - ball.height)
            .closePath()
            .stroke()
            .fill()
            .beginPath()
            .fillStyle('#c54737')
            .strokeStyle ('#c54737')
            .moveTo(ball.x - ball.width, ball.y - ball.height)
            .lineTo(ball.x, ball.y)
            .lineTo(ball.x, ball.y + ball.height * 2)
            .lineTo(ball.x - ball.width, ball.y + ball.height)
            .closePath()
            /*context.stroke();*/
            .fill()
            .beginPath()
            .fillStyle('#f8cd46')
            .strokeStyle('#f8cd46')
            .moveTo(ball.x, ball.y)
            .lineTo(ball.x + ball.width, ball.y - ball.height)
            .lineTo(ball.x + ball.width, ball.y + ball.height)
            .lineTo(ball.x, ball.y + ball.height * 2)
            .closePath()
            .fill()
        //console.log(this)
        //this.parent.update()
    }
}
export default Loading