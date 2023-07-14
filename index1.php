<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width">
  <meta name="keyword" content="amyaz, tarifit, rif, rifain, apprendre, tamazight, berbère, lexique, dictionnaire">
  <meta name="description" content="Apprenez le rifain avec le premier dictionnaire rifain en ligne. En plus, d'un riche lexique en langue berbère, vous disposez de plusieurs verbes conjugués en rifain et une documentation sur la langue rifaine et berbère.">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/navbar.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <title>Mots manquants ?</title>
  <link rel="shortcut icon" href="favicon.png" type="image/png">
  <link rel="stylesheet" href="styleconjug.css">

  <!-- Global site tag (gtag.js) - Google AdSense -->

  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2925504850939512" crossorigin="anonymous"></script>
  
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-67RVRXNX9Q"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-67RVRXNX9Q');
  </script>
</head>

<body>
  <section class="page">
    <!-- Barre de navigation -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="#">
        <img src="logo.jpg" width="40" height="30" class="d-inline-block align-top" alt="">
        AMYAZ
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Dictionnaire</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="conjugaison.html">Conjugaison<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="documentation.html">Documentation</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="index1.php">Mots manquants ?</a>
          </li>
        </ul>
        <li class="form-inline my-2 my-lg-0">
          <a href="#" class="btn btn-dark mr-1">English</a>
        </li>
      </div>
    </nav>



    <!-- Fin de la barre de navigation -->

    <header>

        <style>
            body {
                font-family: Arial;
            }
            
            .comment-form-container {
                background: #F0F0F0;
                padding: 20px;
            }
            
            .input-row {
                margin-bottom: 20px;
            }
            
            .input-field {
                width: 100%;
                padding: 10px;
            }
            
            .btn-submit {
                padding: 10px 20px;
                background: #333;
                color: #f0f0f0;
                font-size: 0.9em;
                width: 100px;
                cursor: pointer;
            }
            
            ul {
                list-style-type: none;
            }
            
            .comment-row {
                border-bottom: #e0dfdf 1px solid;
                margin-bottom: 15px;
                padding: 15px;
            }
            
            .outer-comment {
                background: #F0F0F0;
                padding: 20px;
            }
            
            span.comment-row-label {
                color: #484848;
            }
            
            span.posted-by {
                font-weight: bold;
            }
            
            .comment-info {
                font-size: 0.9em;
                padding: 0 0 10px 0;
            }
            
            .comment-text {
                margin: 10px 0px 30px 0;
            }
            
            .btn-reply {
                color: #2f20d1;
                cursor: pointer;
                text-decoration: none;
            }
            
            .btn-reply:hover {
                text-decoration: underline;
            }
            
            #comment-message {
                margin-left: 20px;
                color: #005e00;
                display: none;
            }
            
            .label {
                padding: 0 0 4px 0;
            }
            </style>

        
            <script src="jquery-3.2.1.min.js"></script>
            
            
            <div class="container py-4">

                <div class="p-5 mb-4 bg-light rounded-3">
                  <div class="container-fluid py-5">
                    <h1 class="display-5 fw-bold">Des mots manquants ?</h1>
                    <p class="text-justify"><p>
                    <p class="text-justify">Si tu ne trouves pas le mot que tu cherches dans le dictionnaire, écris nous le mot manquant en commentaire, nous l'ajouterons dans les plus brefs délais.</p>
                    <p></p>
                    <form id="frm-comment">
                        <div class="input-row">
                            <div class="label">Nom :</div>
                            <input type="hidden" name="comment_id" id="commentId" /> <input
                                class="input-field" type="text" name="name" id="name"
                                placeholder="Entre un nom d'utilisateur" />
                        </div>
                        <div class="input-row">
                            <div class="label">Commentaire :</div>
                            <textarea class="input-field" name="comment" id="comment"
                                placeholder="Écris ton commentaire"></textarea>
                        </div>
                        <div>
                            <input type="button" class="btn-submit" id="submitButton"
                                value="Publie" />
                            <div id="comment-message">Comment added successfully.</div>
                        </div>
                    </form>
                    </div>
                </div>
                <div id="output"></div>
                <script>
                        function postReply(commentId) {
                            $('#commentId').val(commentId);
                            $("#name").focus();
                        }
            
                        $("#submitButton").click(function () {
                               $("#comment-message").css('display', 'none');
                            var str = $("#frm-comment").serialize();
            
                            $.ajax({
                                url: "comment-add.php",
                                data: str,
                                type: 'post',
                                success: function (response)
                                {
                                    var result = eval('(' + response + ')');
                                    if (response)
                                    {
                                        $("#comment-message").css('display', 'inline-block');
                                        $("#name").val("");
                                        $("#comment").val("");
                                        $("#commentId").val("");
                                        listComment();
                                    } else
                                    {
                                        alert("Failed to add comments !");
                                        return false;
                                    }
                                }
                            });
                        });
                        
                        $(document).ready(function () {
                               listComment();
                        });
            
                        function listComment() {
                            $.post("comment-list.php",
                                    function (data) {
                                       var data = JSON.parse(data);
                                        
                                        var comments = "";
                                        var replies = "";
                                        var item = "";
                                        var parent = -1;
                                        var results = new Array();
            
                                        var list = $("<ul class='container py-4 outer-comment bg-light'>");
                                        var item = $("<li>").html(comments);
            
                                        for (var i = 0; (i < data.length); i++)
                                        {
                                            var commentId = data[i]['comment_id'];
                                            parent = data[i]['parent_comment_id'];
            
                                            if (parent == "0")
                                            {
                                                comments = "<div class='container py-4 comment-row bg-light container-fluid'>"+
                                                "<div class='comment-info bg-light'><span class='comment-row-label'>from</span> <span class='posted-by'>" + data[i]['comment_sender_name'] + " </span> <span class='comment-row-label'>at</span> <span class='posted-at'>" + data[i]['comment_at'] + "</span></div>" + 
                                                "<div class='comment-text container py-4 bg-light'>" + data[i]['comment'] + "</div>"+
                                                "<div><a class='btn-reply' onClick='postReply(" + commentId + ")'>Reply</a></div>"+
                                                "</div>";
            
                                                var item = $("<li>").html(comments);
                                                list.append(item);
                                                var reply_list = $('<ul>');
                                                item.append(reply_list);
                                                listReplies(commentId, data, reply_list);
                                            }
                                        }
                                        $("#output").html(list);
                                    });
                        }
            
                        function listReplies(commentId, data, list) {
                            for (var i = 0; (i < data.length); i++)
                            {
                                if (commentId == data[i].parent_comment_id)
                                {
                                    var comments = "<div class='comment-row'>"+
                                    " <div class='comment-info'><span class='comment-row-label'>from</span> <span class='posted-by'>" + data[i]['comment_sender_name'] + " </span> <span class='comment-row-label'>at</span> <span class='posted-at'>" + data[i]['comment_at'] + "</span></div>" + 
                                    "<div class='comment-text'>" + data[i]['comment'] + "</div>"+
                                    "<div><a class='btn-reply' onClick='postReply(" + data[i]['comment_id'] + ")'>Reply</a></div>"+
                                    "</div>";
                                    var item = $("<li>").html(comments);
                                    var reply_list = $('<ul>');
                                    list.append(item);
                                    item.append(reply_list);
                                    listReplies(data[i].comment_id, data, reply_list);
                                }
                            }
                        }
                    </script>
                    </div>
    

    </header>

    <!-- Pied de page -->

    <footer class="bg-dark text-center text-white">
      <!-- Grid container -->
      <div class="container p-4 pb-0">
        <!-- Section: Social media -->
        <section class="mb-4">
          <!-- Facebook -->
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
          </svg>
          <!-- Twitter -->
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
            <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
          </svg>

          <!-- Google -->
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
          </svg>

          <!-- Instagram -->
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
          </svg>

          <!-- Linkedin -->
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
            <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z" />
          </svg>

          <!-- Github -->
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
            <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
          </svg>

          <!-- Section: Social media -->
      </div>
      <!-- Grid container -->

      <!-- Copyright -->
      <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2022 Copyright :
        <a class="text-white">AMYAZ</a>
      </div>
      <!-- Copyright -->
    </footer>
    <!-- Fin du pied de page -->
</body>

</html>