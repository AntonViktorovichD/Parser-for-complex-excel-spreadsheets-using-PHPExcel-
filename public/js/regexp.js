document.addEventListener('DOMContentLoaded', pInpInit);

function pInpInit() {
    let inputs = document.querySelectorAll('.regex');
    for (let inp of inputs) {
        inp.addEventListener('input', onPInpInput);
        inp.addEventListener('click', function () {
            this.lastCaretPos = this.selectionStart;
        });
    }
}

function onPInpInput() {
    if (!this.value.length) {
        this.lastValue = '';
        return;
    }
    let regxpr = this.pattern;
    if (!regxpr)
        return;
    regxpr = new RegExp(regxpr, 'i');
    if (this.value.match(regxpr)) {
        this.lastValue = this.value;
        this.lastCaretPos = this.selectionStart;
    } else {
        this.value = this.lastValue || '';
        let pos = this.lastCaretPos || 0;
        this.setSelectionRange(pos, pos);
        this.classList.remove('anim');
        requestAnimationFrame(() => this.classList.add('anim'));
    }
}
