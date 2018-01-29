/*
* missing components
*/
String.prototype.capitalize = function(s){
  if(typeof(s)=='string'){ return(s.charAt(0).toUpperCase()+s.slice(1)); }
  else{ return(s); }
}

;(function($,document){
    'use strict';

    var $ = $ || window.$;

    if(!$.jo){
        $.jo = {};
    }

    var _settings = {};

    var _selectors = {
        contentId: 'data-id',
        contentBody: 'section.content',
        contentMenu: '#default-menu'
    };

    var _classes = {
        _glyphicon: 'glyphicon glyphicon-%s'
    };

    var _capitalize = String.prototype.capitalize;

    var _loadedScripts = [],
        _isBusy = false;

    var History, Modal, NumberLocale;

    var _init = function(){
        _setLocalDomain();
        History = _history.create();
        Modal = { create: _modal.create };
        NumberLocale = _numberLocale.create({ lang: _lang() });
        $.jo.formManager.init();
        document.dispatchEvent(new Event('jo.init'));
    };

    var _setLocalDomain = function(){
        var name = $('body').attr(_selectors.contentId);
        if(name){
            _settings['domain'] = name;
        }
    };

    var _getGlyph = function(name){
        return _classes._glyphicon.replace('%s',name);
    };

    var _onLoadHtmlHandler = function(e){
        var fwHref = $(this).attr('data-href') || '',
            fwFor = $(this).attr('data-for') || '',
            fwParams = $(this).attr('data-params') || '';
        if(_isBusy!=true && fwHref.length && /^[^#]/.test(fwHref)){
            _isBusy = true;
            $.jo.loadHtml(fwHref,fwFor,fwParams);
        }
        return false;
    };

    var _onPostDataHandler = function(e){
        var fwHref = $(this).attr('data-href') || '',
            fwFor = $(this).attr('data-for') || '',
            fwParams = $(this).attr('data-params') || '',
            callback = eval(fwFor) || null;
        if(fwHref.length && /^[^#]/.test(fwHref)){
            $.jo.loadDatas(fwHref,'','',callback);
        }
        return false;
    };

    var _onPushHtmlHandler = function(e){
        var fwHref = $(this).attr('data-href') || '',
            fwFor = $(this).attr('data-for') || '',
            fwParams = $(this).attr('data-params') || '',
            callback = eval(fwFor) || null;
        if(fwHref.length && /^[^#]/.test(fwHref)){
            $.jo.loadModal(fwHref,'',fwParams,callback);
        }
        return false;
    }
        
    var _proxy = function(url,data,successFn,errorFn,type,method){

        if(!url){ return; }
    
        var onsuccess = successFn || (()=>{});
        var onerror = errorFn || (()=>{});
        
        var _data = data || {};
        var _type = type || 'html';
        var _method = method || 'GET';
        
        var config = {
            url: url,
            method: _method,
            dataType: _type,
            data: _data,
            success: function(response){
                if(typeof(onsuccess)=='function'){
                    onsuccess.call(this,response);
                }
            },
            error: function(xhr,status,message){
                if(typeof(onerror)=='function'){
                    onerror.apply(this,[xhr,status,message]);
                }
            }
        };
        
        $.ajax(config);
        
    };

    var _lang = function () {
        var nav = window.navigator,
            browserLanguagePropertyKeys = ['language', 'browserLanguage', 'systemLanguage', 'userLanguage'],
            i,
            language;

        // support for HTML 5.1 "navigator.languages"
        if (Array.isArray(nav.languages)) {
            for (i = 0; i < nav.languages.length; i++) {
                language = nav.languages[i];
                if (language && language.length) {
                    return language;
                }
            }
        }

        // support for other well known properties in browsers
        for (i = 0; i < browserLanguagePropertyKeys.length; i++) {
            language = nav[browserLanguagePropertyKeys[i]];
            if (language && language.length) {
                return language;
            }
        }

        return null;
    };

    var startDate, endDate;

    var _validDates = {
            en: /^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/,
            fr: /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/
        };

    var _shortDateFr = function(date,full){
        var yyyy = date.getFullYear();
        var month = date.getMonth()+1;
        var mm = month.toString().length<2 ? '0'+ month.toString():month.toString();
        var dd = date.getDate().toString().length<2 ? '0'+ date.getDate():date.getDate();
        var H = full ? ' '+(date.getHours().toString().length<2 ? '0'+ date.getHours():date.getHours()):'';
        var i = full ? ':'+(date.getMinutes().toString().length<2 ? '0'+ date.getMinutes():date.getMinutes()):'';
        var s = full ? ':'+(date.getSeconds().toString().length<2 ? '0'+ date.getSeconds():date.getSeconds()):'';
        return [
            dd, '/',
            mm, '/',
            yyyy,
            H,
            i,
            s
        ].join('');
    };

    var _shortDate2Fr = function(str,full){
        if(_validDates['en'].test(str)){
            if(window.moment){
                var dt = window.moment(str);
                return(dt.isValid() ? dt.format(full ? 'DD/MM/YYYY H:m:s':'DD/MM/YYYY'):'');
            }else{
                return(_shortDateFr(new Date(str),full));
            }
        }else{
            return false;
        }
    };

    var _mysqlDate = function(date,full){
        var yyyy = date.getFullYear();
        var month = date.getMonth()+1;
        var mm = month.toString().length<2 ? '0'+ month.toString():month.toString();
        var dd = date.getDate().toString().length<2 ? '0'+ date.getDate():date.getDate();
        var H = full ? ' '+(date.getHours().toString().length<2 ? '0'+ date.getHours():date.getHours()):'';
        var i = full ? ':'+(date.getMinutes().toString().length<2 ? '0'+ date.getMinutes():date.getMinutes()):'';
        var s = full ? ':'+(date.getSeconds().toString().length<2 ? '0'+ date.getSeconds():date.getSeconds()):'';
        return [
            yyyy, '-',
            mm, '-',
            dd,
            H,
            i,
            s
        ].join('');
    };

    var _shortDate2En = function(str,full){
        if(_validDates['fr'].test(str)){
            if(window.moment){
                var dt = window.moment(str);
                return(dt.isValid() ? dt.format(full ? 'YYYY-MM-DD H:m:s':'YYYY-MM-DD'):'');
            }else{
                return(_mysqlDate(new Date(str),full));
            }
        }else{
            return false;
        }
    };

    // number util object
    var _numberLocale = function(options){
        this.settings = $.extend({
            defaultLocale: 'en-US',
            _toLocale: function(value,options,locale){
                var _locale = locale || this.settings.lang || this.settings.defaultLocale;
                return value.toLocaleString(locale,options);
            },
            _toFloat: function(value,digits,locale){
                return this.settings._toLocale.apply( this, [
                    value || 0.00,
                    {
                        minimumFractionDigits: digits || 2,
                        style: 'decimal',
                        useGrouping: false
                    },
                    locale
                ]);
            },
            _toCurrency: function(value,digits,currency,locale){
                return this.settings._toLocale.apply( this, [
                    value || 0,
                    {
                        minimumFractionDigits: digits || 0,
                        style: 'currency',
                        currency: currency || 'EUR',
                        useGrouping: false
                    },
                    locale
                ]);
        }}, options);
    };

    _numberLocale.create = function(options){
        var numberLocale = new _numberLocale(options);
        return numberLocale;
    };

    var _nlp = _numberLocale.prototype;

    _nlp.toFloat = function(num){
        var fnum = parseFloat(num);
        return this.settings._toFloat.call(this,fnum || 0);
    };

    _nlp.fromString = function(str){
        var _value, _values = str.replace(/[^0-9-,]/i,'').split(',');
        if(_values.length>1){  _value = _values[0] + '.' + ( _values[1].length>0 ? ( _values[1].length>2 ? _values[1].substr(0,2) : ( _values[1].length==1 ? _values[1]+'0' : _values[1] ) ) : '00' ); }
        else{ _value = _values[0]; }
        return this.settings._toFloat.apply(this,[parseFloat(_value),2,this.settings.defaultLocale]);
    };

    _nlp.toString = function(numOrString){
        return this.toFloat(numOrString);
    };

    _nlp.toFloatingCurrency = function(num){
        var fnum = this.toFloat(num);
        return this.settings._toCurrency.apply(this,[fnum,2]);
    };

    _nlp.toInt = function(num){
        var fnum = parseInt(num);
        return this.settings._toFloat.apply(this,[fnum || 0,0]);
    };

    _nlp.toIntegerCurrency = function(num){
        var fnum = parseInt(num);
        return this.settings._toCurrency.call(this,fnum || 0);
    };

    // Modal obj.
    var _modal = function(options){
        this.settings = $.extend({
            id: 'modal-box',
            size: 'modal-md',
            selectors: {
                document: '.modal-dialog',
                header: '.modal-header',
                body: '.modal-body',
                footer: '.modal-footer'
            },
            tpl: [ '<div class="modal fade" tabindex="-1" role="dialog">',
                    '<div class="modal-dialog" role="document">',
                        '<div class="modal-content">',
                            '<div class="modal-header">',
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">',
                                    '<span aria-hidden="true">&times;</span>',
                                '</button>',
                            '</div>',
                            '<div class="modal-body">',
                                '<div class="overlay">',
                                    '<i class="fa fa-refresh fa-spin"></i>',
                                '</div>',
                            '</div>',
                            '<div class="modal-footer">',
                            '</div>',
                        '</div>',
                    '</div>',
                '</div>'
            ].join('')
        }, options);
    };

    _modal.create = function(options){
        var modal = new _modal(options);
        var _s = modal.settings;
        var $el = $(_s.tpl).attr('id',_s.id);
        if(_s.size){
            $(_s.selectors.document,$el).addClass(_s.size);
        }
        if(_s.classes && _s.classes.length){
            $.each( _s.classes, function(i){ $el.addClass(this); });
        }
        if(_s.attributes){
            $.each( _s.attributes, function(i){ $el.attr(i,this); });
        }
        if(_s.title){
            if(typeof(_s.title) == 'string'){
                $('<p>').addClass('h4').text(_s.title).appendTo($(_s.selectors.header,$el));
            }else{
                $(_s.selectors.header,$el).append(_s.title);
            }
        }else{
            $('<p>').addClass('h4').text(' ...').appendTo($(_s.selectors.header,$el));
        }
        if(_s.footerContent){
            if(typeof(_s.footerContent) == 'string'){
                $(_s.selectors.footer,$el).empty().html(_s.footerContent);
            }else{
                $(_s.selectors.footer,$el).empty().append(_s.footerContent);
            }
        }
        if(_s.bodyContent){
            if(typeof(_s.bodyContent) == 'string'){
                $(_s.selectors.body,$el).empty().html(_s.bodyContent);
            }else{
                $(_s.selectors.body,$el).empty().append(_s.bodyContent);
            }
        }
        modal.$el = $el;
        return modal;
    };

    var _m = _modal.prototype;

    _m.show = function(){
        var _this = this;
        var el = this.$el;
        var _s = this.settings;
        var _options = { show: true };
        el.data('settings',_s);
        if(!$('#'+_s.id,$('body')).length){
            $('body').append(el);
        }
        if($.fn.modal){
            el.on('shown.bs.modal', function(){
                _this.onshow.call(_this);
            })
            .on('hidden.bs.modal', function(){
                _this.onhide.call(_this);
            })
            .modal(
                $.extend( _options, ( _s.modalOptions ? _s.modalOptions : {} ))
            );
        }
        return this.$el;
    };

    _m.hide = function(){
        if($('#'+this.settings.id,$('body')).length && this.$el){
            this.$el.remove();
            delete(this.$el);
        }
    };

    _m.onshow = function(){
        if(typeof(this.settings.onshow)=='function'){ this.settings.onshow.call(this,this.$el); }
    };

    _m.onhide = function(){
        if(typeof(this.settings.onhide)=='function'){
            if(this.settings.onhide.call(this,this.$el)!==false){
                this.hide();
            }
        }else{
            this.hide();
        }
    };

    // History obj.
    var _history = function(){
        this._history = [];
        this._max = 3;
    };

    _history.create = function(){
        return new _history();
    };

    var _h = _history.prototype;

    _h.last = function(){
        var url;
        if(this._history.length){
            url = this._history[this._history.length-1];
        }
        return url;
    }

    _h.back = function(){
        var url;
        if(this._history.length-2>0){
            url = this._history.pop(),
            url = this._history.pop();
        }else{
            url = this._history.shift(),
            this._history = [];
        }
        return url;
    };

    _h.push = function(uri){
        this._history.push(uri);
        if(this._history.length>this._max){
            this._history.shift();
        }
    };

    //form manager object
    var _formManager = function(){
        this.settings = {
            count: 0,
            keys: [],
            init: [],
            values: []
        };
    };

    _formManager.create = function(){
        return new _formManager();
    };

    var _fm = _formManager.prototype = new Object();

    _fm.register = function(name,values){
        if(!name){ return false; }
        if(this.isRegistered(name)){ this.remove(name); }
        this.settings.keys[this.settings.count] = name;
        this.settings.values[this.settings.count] = this.settings.init[this.settings.count] = values || undefined;
        this.settings.count++;
        return this.isRegistered(name);
    };

    _fm.remove = function(name){
        var _itis = false;
        var i = this.settings.keys.indexOf(name);
        if(i>-1){
            this.settings.count--;
            delete(this.settings.keys[i]);
            delete(this.settings.init[i]);
            delete(this.settings.values[i]);
            _itis = true;
        }
        return _itis;
    };

    _fm.isRegistered = function(name){
        return this.settings.keys.indexOf(name)>-1;
    };

    _fm.get = function(name){
        var i = this.settings.keys.indexOf(name), _needle;
        if(i>-1){ _needle = $.extend(_needle,this.settings.values[i]); }
        return _needle;
    };

    _fm.set = function(name,values){
        if(!values){ return false; }
        var _datas, _values = values;
        if(this.isRegistered(name)){
            _datas = this.get(name);
            if(_datas){
                $.each(_datas,function(i){
                    if(typeof _values[i] != 'undefined'){
                        _datas[i] = _values[i];
                    }
                });
                this.settings.values[this.settings.keys.indexOf(name)] = _datas;
                return true;
            }
        }
        return false;
    };

    _fm.reset = function(name){
        if(!name){ return false; }
        if(this.isRegistered(name)){
            var values = this.settings.init[this.settings.keys.indexOf(name)];
            if(values){
                return this.applyChange(name,values);
            }
        }
        return false;
    };

    _fm.applyChange = function(name,values){
        if(!this.isRegistered(name)){
            if(values){ this.register(name,values); }
            else{ return false; }
        }else{
            if(values){ this.set(name,values); }
        }
        var _values = this.get(name);
        if(_values){
            if($.fn.validate){
                $('div.form-group','form#'+name).removeClass('has-error');
                $('form#'+name).validate().resetForm();
            }
            $.each( _values, function(field){
                var $el = $('input[id="hidden-'+field+'"]','form#'+name);
                if($el.length){
                    $el.val(this);
                    $el.trigger('change');
                }
            });
            return true;
        }
        return false;
    };

    _fm.check = function(name,values){
        var _values, _itis = true;
        if(!values){ _values = this.settings.init[this.settings.keys.indexOf(name)]; }
        else{ _values = values; }
        if(this.isRegistered(name)){
            var _datas = this.get(name);
            if(_datas){
                $.each(_datas,function(i){
                    if(_values[i]){
                        _itis = _itis && _values[i]==this;
                    }
                });
            }
        }else{
            _itis = false;
        }
        return _itis;
    };

    /* jq plugin */
    $.jo.awaiter = function(element, options){

        var defaults = {
            _method: 'show',
            overlayCls: 'overlay',
            awaiterEl: [
                '<div class="overlay">',
                    '<div class="loader">',
                    '</div>',
                '</div>'
            ].join('')
        };

        var $element = $(element);
        var element = element;
        
        var _awaiter = this;

        _awaiter.settings = {};

        _awaiter.init = function(){
            this.settings = $.extend({}, defaults, options);
            this.$awaiter = $(this.settings.awaiterEl);
            this[this.settings._method]();
        };

        _awaiter.show = function(){
            if(!$('div.'+this.settings.overlayCls,$element).length){
                $element.append(this.$awaiter);
                if(typeof(this.settings.onshow)=='function'){
                    this.settings.onshow.call(this,$element);
                }
            }
        };
        
        _awaiter.hide = function(){
            if($('div.'+this.settings.overlayCls,$element).length){
                this.$awaiter.remove();
            }
            if(typeof(this.settings.onhide)=='function'){
                this.settings.onhide.call(this,$element);
            }
        };

        _awaiter.init();

    };

    $.awaiter = $.jo.awaiter;

    /* jq plugin */
    $.jo.msgbox = function(element, options){

        var defaults = {
            _method: 'show',
            selector: '#message-box',
            modalEl: [
                '<div id="message-box" class="modal fade" tabindex="-1" role="dialog">',
                    '<div class="modal-dialog modal-sm" role="document">',
                        '<div class="modal-content">',
                            '<div class="modal-header">',
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">',
                                    '<span aria-hidden="true">×</span>',
                                '</button>',
                            '</div>',
                            '<div class="modal-body text-primary"></div>',
                        '</div>',
                    '</div>',
                '</div>'
            ].join('')
        };

        var $element = $(element);
        var element = element;
        
        var _modal = this;

        _modal.settings = {};

        _modal.init = function(){
            this.settings = $.extend({}, defaults, options);
            this.$modal = $(this.settings.modalEl);
            this[this.settings._method]();
        };

        _modal.show = function(){
            var _s = this.settings, modal = this.$modal;
            var selector = 'div'+_s.selector, _this = this;
            var _options = { show: true };
            if(!$(selector,$element).length){
                $element.append(modal);
                if(_s.text){
                    $('.modal-body',modal).empty().html(_s.text);
                }
                if(_s.title){
                    $('.modal-header',modal).append($('<h4 class="text-primary">').html(_s.title));
                }
                modal.on('hidden.bs.modal',function(){
                    _this.hide.call(_this);
                }).modal( $.extend( _options,  _s.modalOptions ? _s.modalOptions : {} ));
                if(typeof(_s.onshow)=='function'){
                    _s.onshow.call(this,modal);
                }
            }
        };
        
        _modal.hide = function(){
            var _s = this.settings;
            var selector = 'div'+_s.selector;
            if($(selector,$element).length){
                this.$modal.remove();
                delete(this.$modal);
            }
            if(typeof(_s.onhide)=='function'){
                _s.onhide.call($element);
            }
        };

        _modal.init();

    };

    $.messageBox = $.jo.msgbox;
    
    /* utils */

    $.jo.formManager = {
        manager: null,
        init: function(){
            this.manager = _formManager.create();
        },
        register: function(name,values){
            return this.manager.register(name,values);
        },
        applyChange: function(name,values){
            return this.manager.applyChange(name,values);
        },
        modified: function(name,values){
            return !this.manager.check(name,values);
        },
        set: function(name,values){
            return this.manager.set(name,values);
        },
        get: function(name){
            return this.manager.get(name);
        },
        reset: function(name){
            return this.manager.reset(name);
        },
        remove: function(name){
            return this.manager.remove(name);
        },
        is: function(name){
            return this.manager.isRegistered(name);
        }
    };

    $.jo.getLang = function(){
        return _lang();
    };

    $.jo.formatNumber = function(numOrStr,format){
        var f = format || ''
        switch(f){
            case 'int': return NumberLocale.toInt(numOrStr);
            case 'float': return NumberLocale.toFloat(numOrStr);
            case 'string': return NumberLocale.toString(numOrStr);
            case 'int-currency': return NumberLocale.toIntegerCurrency(numOrStr);
            case 'float-currency': return NumberLocale.toFloatingCurrency(numOrStr);
            default: return NumberLocale.fromString(numOrStr);
        }
    };

    $.jo.formatDate = function(str,format){
        var f = format || '';
        switch(f){
            case 'en': return _shortDate2En(str);
            default: return _shortDate2Fr(str); 
        }
    };

    $.jo.jqXhr = function(url,data,successFn,errorFn,type,method){
        if(!arguments.length){ return; }
        _proxy(url,data,successFn,errorFn,type,method);
    };

    var _timeoutIds = {};
    
    $.jo.jobScheduler = function([...args]){
        if(!args.length){
            for (var _t in _timeoutIds) {
                window.clearTimeout(_timeoutIds[_t]);
            }
            _timeoutIds = {};
            return;
        }
        if(typeof(args[0])=='string'){
            window.clearTimeout(_timeoutIds[args[0]]);
            delete(_timeoutIds[args[0]]);
            return;
        }
        if(args[4] && _timeoutIds.hasOwnProperty(args[4])){
            return;
        }
        if(typeof(args[0])!='function'){
            return;
        }
        var callback = args[0];
        var scope = args[2] || this;
        var interval = args[1] || 15000;
        var endless = args[3] || false;
        var name = args[4] || false;
        var _exe = function(){
            var _t = window.setTimeout(function(){
                callback.call(scope);
                if(endless){ _exe(); }
            }, interval);
            if(name){
                window.clearTimeout(_timeoutIds[name]),
                delete(_timeoutIds[name]),
                _timeoutIds[name] = _t;
            }
        };
        _exe();
    };

    $.jo.print = function(url){
        var $body = $('body'), name = 'ifrm-tmp';
        var _print = function(){

            if($('iframe',$('body')).length){
                $('iframe',$('body'))
                    .off('load')
                    .off('afterprint')
                    .remove();
            }

            var $iframe = $('<iframe src="' + url + '" id="' + name + '" frameborder="0" height="1" width="1">')
                    .css({
                        "position" : "absolute",
                        "bottom" : "1px",
                        "right" : "1px"
                    });
            
            $iframe.appendTo($body);

            $iframe.on( 'load', function(){
                var pWindow = this.contentWindow;
                pWindow.focus();
                pWindow.print();
            });

            $iframe.on( 'afterprint', function(){
                $('#'+name).remove();
            });

        };
        _print();
    };

    $.jo.modalAlert = function(options){

        var _this = this;

        var i18next = window.i18next;
        
        var _options = $.extend({
            type: 'yesno',
            glyph: 'flash',
            modalOptions: {
                backdrop: false
            },
            buttons: {
                default: 'btn-default',
                primary: 'btn-primary'
            },
            tpl: '<button type="button" class="btn btn-flat btn-sm {0}">{1}</button>'
        }, options);

        _options = $.extend( _options, { yesno: function(){

            var $footerContent = $('<div>').addClass('row pad');

            var $buttonYes = $(_options.tpl.replace('{0}',[
                    _options.buttons.primary,
                    'pull-right'
                ].join(' ')).replace('{1}',i18next.t('common.yes')))
                .on( 'click', function(){
                    _modal.hide();
                    _onyes();
                }).appendTo($footerContent);

            var $buttonNo = $(_options.tpl.replace('{0}',[
                    _options.buttons.default,
                    'pull-right',
                    'margin-r-5'
                ].join(' ')).replace('{1}',i18next.t('common.cancel')))
                .on( 'click', function(){
                    _modal.hide();
                    _onno();
                }).appendTo($footerContent);

            var $titleContent = $('<p class="h4 text-red">').css('padding','3px').append('<span class="pull-left '+ _getGlyph(_options.glyph)+'">');
            
            if(_options.title){
                $titleContent.append($('<h4 class="text-primary pull-left margin-none">').text(_options.title));
            }

            var _onyes = function(){
                if(typeof(_options.success)=='function'){
                    _options.success.call(_this);
                }
            };

            var _onno = function(){
                if(typeof(_options.fail)=='function'){
                    _options.fail.call(_this);
                }
            };

            var _modal = Modal.create($.extend({},{
                size: 'modal-sm',
                modalOptions: _options.modalOptions,
                title: $titleContent,
                bodyContent: _options.text || '',
                footerContent: $footerContent,
                onhide: _onno
            }));

            _modal.show();

        }});

        _options[_options.type].call(this);

    };

    $.jo.loadScripts = function(scripts,callback){

      var scripts = scripts || [],
          callback = callback || (()=>{}),
          i = 0;

      var _loadJS = function(file){
        var isFile = $('script[src="'+file+'"]',$(document)).length;
        if(!isFile){
          $.getScript( file, function(){
              _loadedScripts.push(file);
              _load();
          });
        }
      };

      var _loadCSS = function(file){
        var isFile = $('link[href="'+file+'"]',$('head')).length;
        if(!isFile){
            $('<link>', {
                rel: 'stylesheet',
                type: 'text/css',
                href: file
            })
            .on('load',function(){
            _loadedScripts.push(file);
            _load();
            })
            .appendTo('head');
        }
      };

      var _load = function(){
        var file = scripts[i] ? scripts[i]:null;
        i++;
        if(file && $.inArray(file,_loadedScripts)<0){
          var filePart = file.split('.');
          var extPart = filePart[filePart.length-1];
          if(extPart=='css'){ _loadCSS(file); }
          if(extPart=='js'){ _loadJS(file); }
        }else{
          window.setTimeout(()=>{callback.call(this)},100);
        }
      };

      _load();

    };

    $.jo.loadModal = function(strHref,strTarget,strParams,callback){
        var href = strHref, target = strTarget, params = strParams || '', callbackFn = callback || null;
        var $target, $title, settings;
        var _success = function(response){
            if(typeof(callbackFn)=='function'){ response = callbackFn.call($target,response); }
            $target.empty().html(response);
            if($('.form-title',$target).length){
                var _title = $('.form-title',$target).first().val();
                $title.empty().text(_title);
            }
        };
        var _error = function(e){
            var _title = $title.text()+'*';
            $target.addClass('text-warning').empty().text('bla bla ...');
            $title.addClass('text-warning').empty().text(_title);
        };
        var _ready = function(modal){
            settings = modal.data("settings");
            $title = $('p.h4',$(settings.selectors.header,modal));
            $target = $(settings.selectors.content,modal);
            _proxy(href,null,_success,_error,'html','post');
        };
        var config = {
            classes: ['modal-primary'],
            onshow: _ready
        };
        var modal = Modal.create(config);
        var $modal = modal.show();
    };

    $.jo.loadDatas = function(strHref,strTarget,strParams,callback){
      var $div = $('<div>',{id:'overlay'}).addClass('overlay'),
          $li = $('<i>').addClass('fa fa-refresh fa-spin'),
          data = strParams!='' ? $.extend({},{
            token: strParams
          }):{},
          callbackFn = callback || null,
          _success = function(data){
            $('.overlay',$('.overlay-wrapper')).remove();
            if(callbackFn){ callbackFn.apply(this,[data]); }
            else{
                if(data.message){
                    $('body').MessageBox({
                        text: data.message
                    });
                }
            }
          },
          _error = function(e){
            $('.overlay',$('.overlay-wrapper')).remove();
            if(callbackFn){ callbackFn.call(this,e); }
            else{
                $('body').MessageBox({
                    text: 'Error'
                });
            }
          };
      
      if(!$('.overlay',$('.overlay-wrapper')).length){
        $div.append($li).appendTo($('.overlay-wrapper'));
      }

      _proxy(strHref,data,_success,_error,'json','post');

    };

    $.jo.loadHtml = function(strHref,strTarget,strParams,callback){
      var target = strTarget || _selectors.contentBody,
          $target = $(target), 
          $div = $('<div>',{id:'overlay'}).addClass('overlay'),
          $li = $('<i>').addClass('fa fa-refresh fa-spin'),
          href = strHref || '',
          params = strParams || '',
          data = strParams!='' ? $.extend({},{
            token: strParams
          }):{},
          _success = function(data){
            $target.empty().html(data);
            $.jo.initHandlers($target);
            $('.overlay',$('.overlay-wrapper')).remove();
            if(target==_selectors.contentBody){ History.push({ href: href, params: params }); }
            _isBusy = false;
            if(callback){ callback.call(this,data); }
          },
          _error = function(e){
            $('.overlay',$('.overlay-wrapper')).remove();
          };
      
      if(!$('.overlay',$('.overlay-wrapper')).length){
        $div.append($li).appendTo($('.overlay-wrapper'));
      }

      _proxy(href,data,_success,_error,'html','post');

    };

    $.jo.reloadBody = function(){
        var last_url = History.back();
        if(last_url){
            $.jo.loadHtml(last_url.href,'',last_url.params);
        }
    };

    $.jo.reloadAside = function(domain){
        var selector = _selectors.contentMenu,
            prefix = domain || _settings.domain || '';
        $.jo.loadHtml('/./'+(prefix!='' ? prefix+'/':'')+'aside/home',selector,'');
    };

    $.jo.reloadPage = function(domain){
        var domain = domain || _settings.domain || '';
        $.jo.reloadBody();
        $.jo.reloadAside(domain);
    };

    $.jo.refreshPage = function(domain){
        var last_url = History.last();
        if(last_url){
            $.jo.loadHtml(last_url.href,'',last_url.params);
            $.jo.reloadAside(domain);
        }
    };

    $.jo.flash = function(type,title,message,info){
        var _info = info || '',
            _message = title|| '',
            _title = title || '',
            _type = type || 'ok', //  ok|error
            _icon = _type=='ok' ? 'check':'ban';
        if(_type){
            if($.amaran){
                $.amaran({
                    theme: 'awesome ' + _type,
                    content: {
                        title: _title,
                        message: _message,
                        info: _info,
                        icon: 'fa fa-' + _icon
                    },
                    position: 'bottom right',
                    inEffect: 'slideBottom',
                    outEffect: 'slideBottom'
                });
            }
        }
    }

    $.jo.onDownloadedFile = function(form,name,callback){
        var _callback = callback;
        var _name = name;
        var _form = form;
        var _timer, _token;
        var _attempts = 30;
        var setFormCookie = function() {
            _token = new Date().getTime();
            $('<input type=\"hidden\" name=\"'+_name+'\" value=\"'+_token+'\">')
                .appendTo($(_form));
        };
        var expireCookie = function() {
            document.cookie = [
                encodeURIComponent(_name),
                '=; expires=',
                new Date(0).toUTCString(),
                ';path=/',
                ';domain=.'+window.location.host, // ! le point devant le domain !
                ';secure',
            ].join('');
        };
        var getCookie = function() {
            var parts = document.cookie.split(_name + '=');
            if (parts.length == 2) return parts.pop().split(';').shift();
        };
        var unblockSubmit = function() {
            window.clearInterval( _timer );
            expireCookie();
            if(_callback){
                _callback.call(this, _attempts>0);
            }
            _attempts = 30;
        };
        var blockResubmit = function() {
            _timer = window.setInterval( function() {
                var token = getCookie( _name );

                if( (token == _token) || (_attempts == 0) ) {
                    unblockSubmit();
                }

                _attempts--;
            }, 1000 );
        };
        setFormCookie();
        blockResubmit();
    };

    /* jq plugin's defs */
    $.fn.AWaiter = function(options) {
        return this.each(function() {
            var awaiter;
            if(typeof(options)=='string'){ options = { _method: options}; }
            if (undefined == $(this).data('AWaiter')) {
                awaiter = new $.awaiter(this, options);
                $(this).data('AWaiter', awaiter);
            }else{
                awaiter = $(this).data('AWaiter');
                awaiter[options._method]();
            }
        });
    };

    $.fn.MessageBox = function(options) {
        return this.each(function() {
            new $.messageBox(this, options);
        });
    };

    /* datatable renderer defs */
    $.jo.datatableRenderer = function(column) {
        var _column = column || 0,
            _setColumnContent = function(val,type,row){
                switch(_column){
                    case 1:
                        return val;
                    break;
                    case 2:
                        return val;
                    break;
                    default:
                        return val;
                }
            };
        if(_column){
            return function([...args]){
                var val = args[0], type = args[1], row = args[2];
                return _setColumnContent(val,type,row);
            };
        }
        return (()=>{});
    };

    $.jo.footableFormatter = function(name,value) {
        var _name = name || undefined, _value = value || '',
            _getColumnContent = function(val,opt,row){
                if(!row){ return; }
                var _data, _val = val || '', _opt = opt || {}, _row = row;
                switch(true){
                    case _name=='contact-full-name':
                        _data = [
                            _capitalize(_row.nom),
                            _capitalize(_row.pnom)
                        ].join(' ');
                        return $('<span>').text(_data);
                    break;
                    case _name=='contact-full-adress' || _name=='property-full-adress':
                        return $('<span>').text([
                            _row.num>0 ? _row.num:'',
                            _capitalize(_row.street),
                            _row.street!='' ? ',':'',
                            _row.cp,
                            _capitalize(_row.ville)
                        ].join(' '));
                    break;
                    case _name=='contact-edit':
                        _data = _value + '/' + _row.id_user;
                        return $('<a href="javascript:void(0);" data-link="get" data-href="'+_data+'">')
                            .append($('<span>').addClass([_getGlyph('edit'),'text-primary'].join(' ')));

                    break;
                    case _name=='properties-edit':
                        _data = _value + '/' + _row.id_prop;
                        return $('<a href="javascript:void(0);" data-link="get" data-href="'+_data+'">')
                            .append($('<span>').addClass([_getGlyph('edit'),'text-primary'].join(' ')));

                    break;
                    case _name=='property-ptype':
                        var _ref = '';
                        $.each( _row.ptypes, function(){
                            if(this.value==_val){
                                _ref = this.name;
                            }
                        });
                        return _ref;
                    break;
                    case _name=='gesloc-action-pay':
                        _data = _value + '/' + _row.idgesloc;
                        var cls = [
                            _getGlyph( parseInt(_row.endebit)<2 ? 'ok':'euro'),
                            parseInt(_row.endebit)<2 ? 'text-primary':'text-danger'
                        ].join(' ');
                        return $('<a href="javascript:void(0);" data-link="get" data-href="'+_data+'">')
                                .append($('<span>').addClass(cls)); // click managed by init proc.
                    break;
                    case _name=='gesloc-edit-pay':
                        _data = _value;
                        var debitsum = parseFloat(_row.debitsum) || 0, creditsum =parseFloat( _row.creditsum) || 0;
                        var _textcolor = (debitsum-creditsum)<=0 ? 'text-primary':'text-danger';
                        return $('<a href="javascript:void(0);">').data('row',$.extend({},_row))
                                .on('click', function(){
                                    if($.jo.formManager){
                                        $.jo.formManager.applyChange( _data, $.extend( {}, $(this).data('row')) );
                                    }
                                    if($.fn.validate){
                                        $('form#'+_data).validate().form();
                                    }
                                    if($('form#'+_data).parents('.box.collapsed-box').length){
                                        $('form#'+_data).parents('.box.collapsed-box').boxWidget('toggle');
                                    }
                                })
                                .append($('<span>').addClass([_getGlyph('edit'),_textcolor].join(' ')));
                    break;
                    case _name=='gesloc-delete-pay':
                        _data = _value;
                        return $('<a href="javascript:void(0);">')
                                .on('click', function(){
                                    var _success = function(){
                                        var href = _data + '/' + _row.idpay;
                                        $.jo.loadDatas( href, '', '', _done );
                                    };
                                    var _cancel = function(){
                                        // close modal
                                        return true;
                                    };
                                    var _done = function(response){
                                        if(response && response.success){
                                            $.jo.refreshPage(_settings.domain);
                                        }else{
                                            var message = '<p class="h6">';
                                            if(response.message){
                                                if(typeof(response.message)=='object'){
                                                    $.each(response.message,function(i){
                                                        message += '<span>' + i + ':' + this + '</span><br>';
                                                    });
                                                }else{
                                                    message += response.message;
                                                }
                                            }else{
                                                message += window.i18next.t('common.unknow_error');
                                            }
                                            message += '</p>';
                                            $('body').MessageBox({
                                                title: _capitalize(window.i18next.t('site.modalError.title')),
                                                text: window.i18next.t('site.modalError.text_error_alert',{
                                                    infos: message
                                                })
                                            });
                                        }
                                    };
                                    var _check = function(){
                                        var title = window.i18next ? _capitalize(window.i18next.t('site.modalAlert.title')):'Attention';
                                        var text = window.i18next ? _capitalize(window.i18next.t('site.modalAlert.text_geslocpay_del',{
                                            infos: '('+_row.dt_credit+') ref.'+_row.refpay
                                        })):' ...';
                                        $.jo.modalAlert({
                                            title: title,
                                            text: text,
                                            success: _success,
                                            fail: _cancel
                                        });
                                    };
                                    _check();
                                })
                                .append($('<span>').addClass([_getGlyph('remove'),'text-danger'].join(' ')));
                    break;
                    case _name=='gesloc-edit-tenant':
                        _data = _value + '/' + _row.idloc ;
                        return $('<a href="javascript:void(0);" data-link="get" data-href="'+_data+'">').text(_val);
                    break;
                    case _name=='gesloc-edit-owner':
                        _data = _value + '/' + _row.idpro ;
                        return $('<a href="javascript:void(0);" data-link="get" data-href="'+_data+'">').text(_val);
                    break;
                    case _name=='gesloc-full-adress':
                        _data = _value + '/' + _row.id_prop ;
                        return $('<a href="javascript:void(0);" data-link="get" data-href="'+_data+'">').text([
                            _row.num>0 ? _row.num:'',
                            _capitalize(_row.street),
                            _row.street!='' ? ',':'',
                            _row.cp,
                            _capitalize(_row.ville)
                        ].join(' '));
                    break;
                    case _name=='gesloc-pay-gptype':
                        var _ref = '';
                        $.each( _row.gptypes, function(){
                            if(this.value==_val){
                                _ref = this.name;
                            }
                        });
                        return _ref;
                    break;
                    case _name=='short-date-fr':
                        return _shortDate2Fr(_val);
                    break;
                    case _name=='number-gt-zero':
                        return (_val>0 ? _val.toString():'');
                    break;
                    default:
                        return _val;
                }
            };
        if(_name){
            return (function(val,opt,row){
                return _getColumnContent.apply(this,[val,opt,row]);
            });
        }
        return (()=>{});
    };

    $.jo.initHandlers = function(item) {

        $('.sidebar-menu').tree();

        $('[data-toggle="tooltip"] , div.dropdown > a[title]').tooltip();

        $('a[data-link="get"]').off('click').on('click', _onLoadHtmlHandler);

        $('a[data-link="post"]').off('click').on('click', _onPostDataHandler);

        $('a[data-link="push"]').off('click').on('click', _onPushHtmlHandler);

        $('.dropup').off('shown.bs.dropdown')
            .on( 'shown.bs.dropdown', function(){
                var $ul = $('ul',$(this));
                $ul.css('top','-'+($ul.height()+15)+'px');
            }
        );

        $(document).on( 'click', '.btn-history-back', function(){
            $.jo.reloadBody();
        });

        $(document).off('ready.ft.table , after.ft.paging').on('ready.ft.table , after.ft.paging', 'table.footable', function() {
            $('a[data-link="get"]',$(this)).on('click', _onLoadHtmlHandler);
            $('a[data-link="post"]',$(this)).on('click', _onPostDataHandler);
            $('a[data-link="push"]',$(this)).off('click').on('click', _onPushHtmlHandler);
            if($.fn.iCheck){ $('input').iCheck('enable'); }
        });

        if(!$('.overlay-wrapper').length){
            $('<div class="overlay-wrapper">').appendTo($('body'));
        }

        // adminlte init
        if($.fn.boxWidget){
            $('.box').boxWidget();
        }

        // generic init
        if($.fn.iCheck){
            $('input').iCheck({ radioClass: 'iradio_flat-blue' });
        }
        
    };

    $.jo.debugStop = function(){
        var args = arguments;
        var stop = true;
    };

    $.jo.debugPrint = function(mixed){
        switch(typeof(mixed)){
            case 'object':
                window.console.dir(mixed);
            break;
            default:
                window.console.log(mixed);
        }
    };

    $.jo.debugProxy = function(fn,scope,args){
        var _fn = fn || null, _scope = scope || {}, _args = args || [];
        var result, stop;
        if(typeof(_fn)=='function'){
            result = _fn.apply(_scope,_args);
            $.jo.DebugStop(result);
        }
    };

    _init();

})(window.jQuery,window.document);
