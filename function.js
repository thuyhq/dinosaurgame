// list common functions
var Funcs = function () {
    this.container = document.querySelectorAll(".party-group")[0];
    this.initialize();
};

Funcs.prototype.initialize = function () {
    var self = this;

    $('#avatar-file').bind("change", function () {
        var fr = new FileReader;
        var size = this.files[0].size;

        fr.onload = function () { // file is loaded
            var img = new Image;
            img.onload = function () {
                console.log(size);
                var hasError = false;
                console.log(img.width);
                console.log(img.height);
                if (img.width < 50 || img.height < 50) {
                    self.act.errorMin();
                    hasError = true;
                }
                if (img.width > 1500 || img.height > 1500) {
                    self.act.errorMax();
                    hasError = true;
                }
                if (size > (2 * 1024 * 1024)) {
                    self.act.errorSize();
                    hasError = true;
                }

                if (hasError) {
                    $('#save-avatar').addClass('hide');
                    self.act.showError(function () {
                        self.act.show()
                    })
                } else {
                    $('#save-avatar').removeClass('hide');
                    self.act.showOk(function () {
                        self.act.show()
                    })
                }
            };
            img.src = fr.result;
            console.log("im src");
            console.log(img.src);
        };
        fr.readAsDataURL(this.files[0]);
    })

    // $('.image-editor').cropit({
    //     smallImage: "stretch"
    // });

    self.act = function () {
        function errorSize() {
            $('#err-zise').removeClass('hide');
        }

        function errorMin() {
            $('#err-min').removeClass('hide');
        }

        function errorMax() {
            $('#err-max').removeClass('hide');
        }

        function showError(callback) {
            // $('.cropit-preview').addClass('hide');
            // $('.cropit-image-zoom-input').addClass('hide');
            callback();
        }

        function showOk(callback) {
            clearError();
            // $('.cropit-preview').removeClass('hide');
            // $('.cropit-image-zoom-input').removeClass('hide');
            callback();
        }

        function show() {
            self.container.style.display = "flex";
            $('#room-id').val(configLink.url_server+'?roomid=' + generateID());
            $('#save-avatar').attr({
                onclick: "Func.act.saveAvatar()",
                value: "Save"
            });
            $('#select2-avatar-place').show();
        }

        function hide() {
            $('#avatar-file').val('');
            self.container.style.display = "none";
        }

        function select() {
            clearError();
            $('#avatar-file').trigger('click')
        }

        function clearError() {
            $('#err-size').addClass('hide');
            $('#err-min').addClass('hide');
            $('#err-max').addClass('hide');
        }

        function saveAvatar() {
            var imageData = $('.image-editor').cropit('export');
            var data = {
                avatar: imageData
            }
            $('#save-avatar').attr({
                onclick: "",
                value: "Uploading ..."
            });

            $('#select2-avatar-place').hide();
            $.ajax({
                method: 'POST',
                url: '/user/save-avatar.php',
                data: $.param(data),
                success: function (res) {
                    if (res.success) {
                        $('#user-avatar').attr({
                            src: res.fullAvatar
                        });
                        hide();
                    }
                    $('#save-avatar').attr({
                        onclick: "Func.act.saveAvatar()",
                        value: "Save"
                    });
                    $('#select2-avatar-place').show();
                }
            });
        }

        return {
            errorMin: function () {
                errorMin();
            },
            errorMax: function () {
                errorMax();
            },
            errorSize: function () {
                errorSize();
            },
            show: function () {
                show()
            },
            hide: function () {
                hide()
            },
            select: function () {
                select()
            },
            saveAvatar: function () {
                saveAvatar()
            },
            showError: function (callback) {
                showError(callback)
            },
            showOk: function (callback) {
                showOk(callback)
            }
        }
    }();
};

function generateID() {
    var ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var ID_LENGTH = 8;
    var rtn = '';
    for (var i = 0; i < ID_LENGTH; i++) {
        rtn += ALPHABET.charAt(Math.floor(Math.random() * ALPHABET.length));
    }
    return rtn;
}

/**
 * Fixed an error when entering data into password box
 * @type {{init}}
 */
var FixInputPassword = function () {
    var addEventKeyup = function (el) {
        el.addEventListener("keyup", function (e) {
            var f = ((this.value || "").trim() == "") ? "Rubik" : "Tahoma";
            this.style.fontFamily = f;
        }, false);
    };

    var addEventFocus = function (el) {
        el.addEventListener("focus", function (e) {
            var f = ((this.value || "").trim() == "") ? "Rubik" : "Tahoma";
            this.style.fontFamily = f;
        }, false);
    };

    var init = function () {
        var p = document.querySelectorAll("input[type='password']"), l = p.length;
        if (l) for (var i = 0; i < l; i++) {
            addEventKeyup(p[i]);
            addEventFocus(p[i]);
        }
    };
    return {
        init: function () {
            init();
        }
    };

}();