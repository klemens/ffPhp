var ffPhp = {
    Init: function() {
        $('form.ffphp').submit(ffPhp.BasicCheck);
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
    },
    BasicCheck: function() {
        var ok = true;
        $('label > em', this).each(function() {
            //Check input fields
            var input = $(this.parentNode.parentNode.getElementsByTagName('div')[0]).children('input[type=text],input[type=password],textarea');
            if(input.length) {
                if($.trim(input.val()) === '') {
                    ok = false;
                    input.addClass('ffphp-error');
                } else {
                    input.removeClass('ffphp-error');
                }
            }
        });
        return ok;
    }
};
