function initColorPicker() {
    $('.colorpicker input[type="text"]').each((i, picker) => {
        const $picker = $(picker);

        if (picker.value) {
            updateColors($picker, picker.value);
        }

        $(picker).colorpicker()
            .on('colorpickerChange', (event) => {
                updateColors($(event.target), event.color.toString());
            });
    });
}

function updateColors($picker, hex) {
    const rgb = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    if (rgb) {
        const r = parseInt(rgb[1], 16);
        const g = parseInt(rgb[2], 16);
        const b = parseInt(rgb[3], 16);

        const bgColor = `rgba(${r}, ${g}, ${b}, 0.5)`;
        $picker.css('background-color', bgColor);

        const brightness = (r * 299 + g * 587 + b * 114) / 1000;

        $picker.css('color', brightness < 128 ? 'white' : 'black');
    } else {
        $picker.css('background-color', hex);
        $picker.css('color', 'black');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    initColorPicker();
});
