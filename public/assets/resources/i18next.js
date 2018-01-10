;((function(i18){
if(i18){
    i18next.init({
        lng: 'en',
        debug: true,
        resources: {
            en: {
                translation: {
                    common:{
                        "yes": "yes",
                        "no": "no",
                        "cancel": "cancel",
                        "error": "error",
                        "attention": "attention",
                        "unknow_error": "unknow error"
                    },
                    site:{
                        modalAlert:{
                            "title": "$t(common.attention)",
                            "text_geslocpay_del": "<p>Do you want to delete this entry ?</p><p>payment of {{infos}}</p>"
                        },
                        modalError:{
                            "title": "$t(common.error)",
                            "text_error_alert": "<p>An error has occurred:</p><p>{{- infos}}</p>"
                        }
                    }
                }
            },
            fr: {
                translation: {
                    common:{
                        "yes": "oui",
                        "no": "non",
                        "cancel": "annuler",
                        "error": "erreur",
                        "attention": "attention",
                        "unknow_error": "erreur inconnue"
                    },
                    site:{
                        modalAlert:{
                            "title": "$t(common.attention)",
                            "text_geslocpay_del": "<p>Voulez-vous supprimer cette entrée ?</p><p>payement du {{infos}}</p>"
                        },
                        modalError:{
                            "title": "$t(common.error)",
                            "text_error_alert": "<p>Une erreur est survenue:</p><p>{{- infos}}</p>"
                        }
                    }
                }
            },
            nl: {
                translation: {
                    common:{
                        "yes": "ya",
                        "no": "nee",
                        "cancel": "annuleren",
                        "error": "probleem",
                        "attention": "aandacht",
                        "unknow_error": "onbekende fout"
                    },
                    site:{
                        modalAlert:{
                            "title": "$t(common.attention)",
                            "text_geslocpay_del": "<p>Wilt u dit item verwijderen ?</p><p>betaling van {{infos}}</p>"
                        },
                        modalError:{
                            "title": "$t(common.error)",
                            "text_error_alert": "<p>Er is een fout opgetreden:</p><p>{{- infos}}</p>"
                        }
                    }
                }
            }
        }},
        function(err, t) {
            console.log('i18next loaded.');
        }
    );
}
})(window.i18next,window.document))