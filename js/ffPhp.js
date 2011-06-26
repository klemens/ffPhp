var ffPhp = {
    Init: function() {
        $('form.ffphp > fieldset > legend > span').each(function() {
            $(this).click(ffPhp.ToggleFieldset);
            if(this.innerHTML === '▲')
                this.parentNode.parentNode.getElementsByTagName('ol')[0].style.display = 'none';
        });
    },
    ToggleFieldset: function() {
        var ol = this.parentNode.parentNode.getElementsByTagName('ol')[0];
        if(ol.style.display == 'none') {
            ol.style.display = 'block';
            this.innerHTML = '▼';
        } else {
            ol.style.display = 'none';
            this.innerHTML = '▲';
        }
        return true;
    }
};
