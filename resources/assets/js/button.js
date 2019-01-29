import cax from 'cax'

const {Graphics, Bitmap, Text, Group} = cax

class Button extends Group {
    constructor(option) {
        super();
        this.width = option.width;
        this.roundedRect = new cax.RoundedRect(option.width, option.height, option.r, {
            fillStyle: option.fillStyle,
            strokeStyle: option.strokeStyle,
            lineWidth: option.lineWidth
        });
        this.text = new cax.Text(option.text, {
            font: option.font,
            color: option.color
        });

        this.text.x = option.width / 2 - this.text.getWidth() / 2 * this.text.scaleX;
        this.text.y = option.height / 2 - 12 + 5 * this.text.scaleY;
        this.add(this.roundedRect, this.text)
    }

    setColor(color) {
        this.roundedRect.option.fillStyle = color;
        this.roundedRect.option.strokeStyle = color;
    }

    setText(str) {
        this.text.text = str;
    }
}

export default Button