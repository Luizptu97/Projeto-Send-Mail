<?php

require "./bibliotecas/PHPmailer/Exception.php";
require "./bibliotecas/PHPmailer/OAuth.php";
require "./bibliotecas/PHPmailer/PHPMailer.php";
require "./bibliotecas/PHPmailer/POP3.php";
require "./bibliotecas/PHPmailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Mensagem{
    private $para = null;
    private $assunto = null;
    private $mensagem = null;
    public $status = array('codigo_status' => null, 'descricao_status' => '');

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    public function mensagemValida(){
      if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
          return false;
        }
        return true;
    }

}

$mensagem = new Mensagem();


$mensagem->__set('para', utf8_decode($_POST['para']));
$mensagem->__set('assunto', utf8_decode($_POST['assunto']));
$mensagem->__set('mensagem', utf8_decode($_POST['mensagem']));


if(!$mensagem->mensagemValida()){
    $mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = 'NÃO FOI POSSÍVEL ENVIAR ESTE EMAIL! FAVOR PREENCHER CORRETAMENTE OS CAMPOS';
    
}else{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'lgsilveira97@gmail.com';                     //SMTP username
        $mail->Password   = 'luiz#12345';                               //SMTP password
        $mail->SMTPSecure = 'tls';                                  //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('lgsilveira97@gmail.com', 'App Send Mail');
        $mail->addAddress($mensagem->__get('para'));     //Add a recipient
        $mail->addReplyTo('lgsilveira97@gmail.com', 'RESPOSTA');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
    
        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        $mail->AltBody = 'A mensagem não pôde ser exibida';
    
        $mail->send();

        $mensagem->status['codigo_status'] = 1;
        $mensagem->status['descricao_status'] = 'Email enviado com sucesso!';
        

    } catch (Exception $e) {

        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = 'NÃO FOI POSSÍVEL ENVIAR ESTE EMAIL! Detalhes do erro: ' . $mail->ErrorInfo;



    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <title>App Send Mail</title>
</head>
<body>
    
    <div class="container">
        <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
 
        <div class="row">
            <div class="col-md-12"> 
                <? if($mensagem->status['codigo_status'] == 1) { ?>
                   
                  <div class="container ">
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                      <h1 class="display-4 text-success text-center">Sucesso</h1>
                      <p class="text-center"><?= $mensagem->status['descricao_status'] ?></p>
                      <div class=" d-flex justify-content-center">
                      <a href="index.php" class="btn btn-success btn-lg mt-5 text-white"> Voltar </a>
                      </div>
                  </div>  
                    

                <? } ?>  

                <? if($mensagem->status['codigo_status'] == 2) { ?>
                   
                    <div class="container ">
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                      <h1 class="display-4 text-danger text-center">Falha ao enviar</h1>
                      <p class="text-center"><?= $mensagem->status['descricao_status'] ?></p>
                      <div class=" d-flex justify-content-center">
                      <a href="index.php" class="btn btn-danger btn-lg mt-5 text-white"> Voltar </a>
                      </div>
                  </div>  
                    

                <? } ?>


            </div>
        </div>    

    </div>

</body>
</html>
