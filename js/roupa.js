$(document).ready(function() {
    $("#frmRoupa").submit(function(event) {
        var ehValido = form[0].checkValidity();
        
        // cancels the form submission
        event.preventDefault();

        if (ehValido)
        {
            var mensagem = $("#mensagem");

            if (!mensagem.hasClass("d-none"))
            {
                mensagem.addClass("d-none");
            }

            submitForm();
        }
    });

    //TODO: mudar para aplicar para roupa

    function submitForm(){
        // Initiate Variables With Form Content
        var usuario = $("#usuario").val();
        var senha = $("#senha").val();
    
        $.ajax({
            type: "POST",
            url: "exec/versenha.php",
            data: "usuario=" + usuario + "&senha=" + senha,
            success : function(text) {
                if (text == "OK") {
                    formSuccess();
                }
                else {
                    var mensagem = $("#mensagem");

                    mensagem.removeClass("d-none");
                    mensagem.html("<i class=\"material-icons\">&#xE002;</i> O usuário ou a senha que você digitou estão errados." +
                                  "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>");
                }
            }
        });
    }

    function formSuccess()
    {
        location.href = "principal.php";
    }
});