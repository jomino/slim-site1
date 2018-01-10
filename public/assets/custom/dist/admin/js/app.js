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

    var _capitalize = String.prototype.capitalize;

    var _loadedScripts = [];

    var _init = function(){
        document.dispatchEvent(new Event('jo.init'));
    };

    var _onLoadHtmlHandler = function(e){
        var fwHref = $(this).attr('data-href') || '',
            fwFor = $(this).attr('data-for') || '',
            fwParams = $(this).attr('data-params') || '';
        if(fwHref.length && /^[^#]/.test(fwHref)){
            $.jo.loadHtml(fwHref,fwFor,fwParams);
        }
        return false;
    };
    
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

    var startDate, endDate;

    var _shortDateFr = function(date){
        var yyyy = date.getFullYear();
        var month = date.getMonth()+1;
        var mm = month.toString().length<2 ? '0'+ month.toString():month.toString();
        var dd = date.getDate().toString().length<2 ? '0'+ date.getDate():date.getDate();
        return [
            dd, '/',
            mm, '/',
            yyyy
        ].join('');
    };

    var _mysqlDate = function(date){
        var yyyy = date.getFullYear();
        var month = date.getMonth()+1;
        var mm = month.toString().length<2 ? '0'+ month.toString():month.toString();
        var dd = date.getDate().toString().length<2 ? '0'+ date.getDate():date.getDate();
        return [
            yyyy, '-',
            mm, '-',
            dd, ' ',
            '00:00:00'
        ].join('');
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
            }
            if(typeof(this.settings.onshow)=='function'){
                this.settings.onshow.call(this);
            }
        };
        
        _awaiter.hide = function(){
            if($('div.'+this.settings.overlayCls,$element).length){
                this.$awaiter.remove();
            }
            if(typeof(this.settings.onhide)=='function'){
                this.settings.onhide.call(this);
            }
        };

        _awaiter.init();

    };

    $.awaiter = $.jo.awaiter;
    
    /* utils */

    $.jo.jqXhr = function([...args]){
        if(!args.length){ return; }
        var url = args[0];
        var data = args[1] || {};
        var successFn = args[2] || undefined;
        var errorFn = args[3] || undefined;
        var type = args[4] || undefined;
        var method = args[5] || undefined;
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

    $.jo.loadScripts = function(scripts,callback){

      var scripts = scripts || [],
          callback = callback || (()=>{}),
          i = 0;

      var _loadJS = function(file){
          $.getScript( file, function(){
              _loadedScripts.push(file);
              _load();
          });
      };

      var _loadCSS = function(file){
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

    $.jo.loadHtml = function(strHref,strTarget,strParams,callback){
      var $target = strTarget!='' ? $(strTarget):$('section.content'), 
          $div = $('<div>',{id:'overlay'}).addClass('overlay'),
          $li = $('<i>').addClass('fa fa-refresh fa-spin'),
          data = $.extend({},{
            token: strParams
          }),
          _success = function(data){
            $target.empty().html(data);
            $.jo.initHandlers($target);
            $('.overlay',$('.overlay-wrapper')).remove();
            if(callback){ callback.call(this); }
          },
          _error = function(e){
              // todo
          };
      
      if(!$('.overlay',$('.overlay-wrapper')).length){
        $div.append($li).appendTo($('.overlay-wrapper'));
      }

      _proxy(strHref,data,_success,_error,'html','POST');

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

    $.jo.footableFormatter = function(column) {
        var _column = column || '',
            _getColumnContent = function(val,opt,row){
                if(!row){ return; }
                var text;
                switch(_column){
                    case 'contact-full-name':
                        text = [
                            _capitalize(row.nom),
                            _capitalize(row.pnom)
                        ].join(' ');
                        return $('<span>').text(text).prop('outerHTML');
                    break;
                    case 'contact-full-adress':
                        text = [
                            row.num>0 ? row.num:'',
                            _capitalize(row.street),
                            row.street!='' ? ',':'',
                            row.cp,
                            _capitalize(row.ville)
                        ].join(' ');
                        return $('<span>').text(text).prop('outerHTML');
                    break;
                    default:
                        return val;
                }
            };
        if(_column){
            return (function(val,opt,row){
                return _getColumnContent.apply(this,[val,opt,row]);
            });
        }
        return (()=>{});
    };

    $.jo.initHandlers = function(item) {

        $('[data-toggle="tooltip"]').tooltip();

        $('[data-link="get"]').off('click').on('click', _onLoadHtmlHandler);

    };

    $.jo.DebugStop = function(){
        var stop = true;
    };

    _init();

})(window.jQuery,window.document);
