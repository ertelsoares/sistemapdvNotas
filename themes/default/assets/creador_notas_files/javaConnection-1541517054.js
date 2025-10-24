var JavaConnection = function (type, element) {
    var instance;

    function createInstance(type, element) {
        if (type === "appJava") {
            return new ApplicationFunctions();
        } else {
            return new AppletFunctions(element);
        }
    }

    return {
        getInstance: function () {
            if (!instance) {
                instance = createInstance(type, element);
            }
            return instance;
        }
    };
};

var ApplicationFunctions = function () {
    var url = new getServerUrl();

    this.config = function (parameters, callback) {
        CallMethod("/config", parameters, function (err, data) {
            try {
                data = JSON.parse(data);
                if (!err && !data.err) {
                    callback(false, data.info);
                } else {
                    callback(true, data.info);
                }
            } catch (e) {
                callback(err, data);
            }
        });
    };

    this.getAtr = function(parameters, callback) {
        CallMethod("/getAppVersion", "", function (vErr, vData) {
            if (!vErr && vData >= 1.4) {
                CallMethod("/getAtr", parameters, function(err, data) {
                    try {
                        data = JSON.parse(data);
                        saveAtr(data);
                    } catch (e) {
                        callback(err, data);
                    }
                });
            }
        });
    };

    this.getMessages = function (callback) {
        CallMethod("/getMessages", "", function (err, data) {
            if (!err && data == "") {
                callback(false, data);
            } else {
                callback(true, data);
            }
        });
    };

    this.getAdditionalInformation = function (callback) {
        CallMethod("/getAdditionalInformation", "", function (err, data) {
            callback(err, data);
        });
    };


    this.choosePathToFile = function (callback) {
        CallMethod("/choosePathToFile", "", function (err, data) {
            callback(err, data);
        });
    };

    this.signXml = function (xml, callback) {
        CallMethod("/runService", xml, function (err, data) {
            try {
                data = JSON.parse(data);
                if (!err && !data.err) {
                    callback(false, data.info);
                } else {
                    callback(true, data.info);
                }
            } catch (e) {
                callback(err, data);
            }
        });
    };

    this.getListOfAliases = function (callback) {
        CallMethod("/getListOfAliases", "", function (err, data) {
            callback(err, data);
        });
    };

    this.checkAutoLibSelection = function (callback) {
        CallMethod("/getAppVersion", "", function (err, data) {
            if (!err && data >= 1.2) {
                callback(false, data);
            } else {
                callback(true, "Versão não suporta seleção automática");
            }
        });
    };

    this.sendToPrinter = function (parameters, callback) {
        CallMethod("/sendToPrinter", parameters, function (err, data) {
            callback(err, data);
        });
    };

    this.writeToFile = function (parameters, callback) {
        CallMethod("/writeToFile", parameters, function (err, data) {
            callback(err, data);
        });
    };

    function CallMethod(endpoint, parameters, callback) {
        $.ajax({
            type: 'GET',
            url: url.protocol + "://" + url.ip + ":" + url.port + endpoint,
            data: {parameters: encode64(JSON.stringify(parameters))},
            dataType: 'text',
            success: function (data) {
                callback(false, (data != "" ? fixString(decode64(data)) : ""));
            },
            error: function (xhr) {
                verifyErrorCode(xhr.status, function(info) {
                    callback(true, info);
                });
            }
        });
    };
        
    function getServerUrl() {
        opt = {protocol: "https", port: "8003", ip: "127.0.0.1"};
        BrowserDetect.init();
       
        if ((BrowserDetect.browser === "Chrome" && BrowserDetect.version >= 53) || (BrowserDetect.browser === "Firefox" && BrowserDetect.version >= 55)) {
            opt.protocol = "http";
            opt.port = "8004";
        }

        return opt;
    }

};

var AppletFunctions = function (element) {

    this.config = function (parameters, callback) {
        callAppletFunction(element, "config", parameters, function (err, data) {
            try {
                data = JSON.parse(data);
                if (!err && !data.err) {
                    callback(false, data.info);
                } else {
                    callback(true, data.info);
                }
            } catch (e) {
                callback(err, data);
            }
        });
    };

    this.getMessages = function (callback) {
        callAppletFunction(element, "getMessages", null, function (err, data) {
            if (!err && data == "") {
                callback(false, data);
            } else {
                callback(true, data);
            }
        });
    };

    this.getAdditionalInformation = function (callback) {
        callAppletFunction(element, "getAdditionalInformation", null, function (err, data) {
            callback(err, data);
        });
    };

    this.choosePathToFile = function (callback) {
        callAppletFunction(element, "choosePathToFile", null, function (err, data) {
            callback(err, data);
        });
    };

    this.signXml = function (xml, callback) {
        callAppletFunction(element, "runService", xml, function (err, data) {
            try {
                data = JSON.parse(data);
                if (!err && !data.err) {
                    callback(false, data.info);
                } else {
                    callback(true, data.info);
                }
            } catch (e) {
                callback(err, data);
            }
        });
    };

    this.getListOfAliases = function (callback) {
        callAppletFunction(element, "getListOfAliases", null, function (err, data) {
            callback(err, data);
        });
    };

    this.sendToPrinter = function (parameters, callback) {
        callAppletFunction(element, "sendToPrinter", parameters, function (err, data) {
            callback(err, data);
        });
    };

    this.writeToFile = function (parameters, callback) {
        callAppletFunction(element, "gerarArquivo", parameters, function (err, data) {
            callback(err, data);
        });
    };

    this.checkAutoLibSelection = function (callback) {
        callback(false, "");
    }

    function callAppletFunction(element, method, parameters, callback) {
        var err = false;
        var data = "";
        try {
            if (!parameters) {
                data = window["document"][element][method]();
            } else {
                data = window["document"][element][method](encode64(JSON.stringify(parameters)));
            }

        } catch (e) {
            BrowserDetect.init();
            if (BrowserDetect.browser == "Chrome" && BrowserDetect.version >= 42) {
                data = "Sua versão do Google Chrome não suporta applets " + ((window.location.host).indexOf("bling") > -1 ? "<a href='#' onclick='showChromeDialog()'>Saiba mais</a>" : "");
            } else {
                data = "Java não habilitado em seu navegador " + ((window.location.host).indexOf("bling") > -1 ? "<a href='http://manuais.bling.com.br/manual/?item=passos-iniciais-para-emissao-da-nfe#MozillaFirefox' target='_blank'>Saiba mais</a>" : "");
            }
            err = true;
        }
        callback(err, data);
    }
    ;
};

function showChromeDialog() {
    $('<div></div>').dialog({
        title: "Aviso de compatibilidade",
        resizable: false,
        width: 750,
        height: 310,
        modal: true,
        create: function () {
            $(this).html("<div ><p align='justify' style='line-height: 125%;'>Seu navegador não possui suporte à aplicações NPAPI (Netscape Plugin Programming Interface).<br><br>" +
                    "Você pode continuar emitindo suas notas fiscais utilizando uma das alternativas abaixo: " +
                    "<ol align='left'>" +
                    "<li>Utilizar o navegador Mozilla Firefox ou qualquer outro que possua suporte à aplicações NPAPI.</li>" +
                    "<li>Realizar o download da aplicação para assinaturas de notas fiscais, seguindo as instruções presentes em nosso <a href='http://manuais.bling.com.br/manual/?item=passos-iniciais-para-emissao-da-nfe#Configuraçãoviaaplicaçãoinstalada'>manual</a>.</li>" +
                    "<li>Habilitar manualmente a permissão para execução de aplicações NPAPI. (Não recomendado)</li>" +
                    "</ol></p><p align='justify'>Para maiores informações, acesse nosso <a href='http://manuais.bling.com.br/manual/?item=passos-iniciais-para-emissao-da-nfe#Configuraçãoviaaplicaçãoinstalada'>manual de configuração</a>.</p></div>");
        },
        close: function () {
            $(this).dialog("destroy");
        }
    });
}

function verifyErrorCode(xhr, callback) {
    function verify(xhr, adBlockEnabled) {
        var info = "";
        switch (xhr) {
            case 0:
                if(adBlockEnabled) {
                    info = '<b>A extensão AdBlock do seu navegador está impossibilitando o funcionamento deste componente.</b></br>Desative a extensão ou adicione esta página como exceção.';
                } else {
                    info = "Não foi possível conectar o aplicativo emissor de NFe's " + ((window.location.host).indexOf("bling") > -1 ? "<a href='http://manuais.bling.com.br/manual/?item=passos-iniciais-para-emissao-da-nfe' target='_blank'>Saiba mais</a>" : "");
                }
                break;
            case 5:
                info = "A Aplicação BlingNfe está desatualizada";
                break;
            default:
                break;
        }
        return info;
    }
    isAdblockEnabled(function(isEnabled) {
        callback(verify(xhr, isEnabled));
    });
}

function fixString(data) {
    try {
        fixedstring = decodeURIComponent(escape(data));
    } catch (e) {
        fixedstring = data;
    }

    return fixedstring;
}
