<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../css/style.css" media="screen" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <title>Atendimento</title>
</head>
<script>
  $(document).ready(function () {
      $.ajax({
          url: "../../controllers/UserController.php",
          dataType: 'json',
          success: function (data) {
              //Faz um ForEach do retorno do arrayJSON
              $.each(data, function (idx, obj){
                  //Dentro da tabela > tBody ele cria as propriedades
                  $('#tabela > tbody').append('<tr><td>'+obj.id+'</td><td>'+obj.name+'</td><td>'+obj.username+'</td><td>'+obj.password+'</td></tr>');
              });
          }
      });

  });
  
</script>
<body>
  <div class="container">
    <nav class="row">
      <ul class="ul">
        <li><a href="/">Home</a></li>
        <li><a href="/views/attendance/">Atendimento</a></li>
        <li><a href="/share/orm/">BD</a></li>
      </ul>
    </nav>
    <table id="tabela">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Nome de Usuario</th>
                <th>Senha</th>
            </tr>
        </thead>
        <tbody>
          
        </tbody>
    </table>
  </div>
</body>
</html>