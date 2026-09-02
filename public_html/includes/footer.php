<section class='bloco-conteudo bg-footer' id='footer'>

	<div class='bloco-conteudo-padding'>
		<div class="col-md-2 col-sm-12">
			<img src="<?php echo ROOT; ?>resources/img/logo-footer.svg" alt="Santa Casa Lorena" class="img-responsive logo-footer">
		</div>

		<div class="col-md-4 col-sm-12">
			<h2>portal da Transparência</h2>
			<br>
			<p class='fonte2'>
				Fique por dentro do detalhamento das despesas da Irmandade da Santa Casa de Misericórdia de Lorena.
			</p>
			<a class='upper' href='<?php  echo ROOT."institucional/portal_transparencia"; ?>'>clique aqui<i class='fa fa-fw fa-arrow-right'></i></a>
		</div>

		<div class="col-md-3 col-sm-12">
			<h2>contato</h2>
			<br>
			<p>
				<img src='<?php echo ROOT; ?>resources/img/localizacao.svg' class='icons'>
				<span class='fonte2 f-white'>
					<?php echo $localizacao['localizacao']; ?>
				</span>
				

				<br>
				<br>

				<img src='<?php echo ROOT; ?>resources/img/telefone.svg' class='icons telefone'>
				<span class='fonte2 f-white'>
					<?php echo $localizacao['telefone']; ?>
				</span>
				<span class='fonte2 f-white'>
					/ (12) 3159-3349
				</span>
			</p>
		</div>

		<div class="col-md-3 col-sm-12">
			<form method="post" action="#footer" id="form_news">
				<h2>newsletter</h2>
				<br>
				<div class="form-group">
					<div class="input-bloco">
		                <input type="text" class="form-control" placeholder="seu e-mail" id="newsletter" name="newsletter">
		                <button type="submit"><i class="fa fa-fw fa-angle-right"></i></button>
		            </div>
		            
		            <?php 

                         if(isset($dados['newsletter'])):

                           	if($dados['newsletter'] == ""){
                               	echo "<label class='msg-erro' style='color:#e31412; display:block;'>Todos os campos são obrigatórios</label>";
                            }else{
                                        
                                $dados = array_map('strip_tags', $dados);
                                $dados = array_map('trim', $dados);
                                $readN = new Read();
                                $readN->fullRead("SELECT email FROM ".PREFIX."newsletter WHERE email = :email", "email={$dados['newsletter']}");

                                if($readN->getResult()):
                                    echo "<label class='msg-erro' style='color:#e31412; display:block;'>Este email ja foi cadastrado. Obrigado!</label>";
                                else:
                                    $dados['data'] = date("Y-m-d");
                                    $dados['email'] = $dados['newsletter'];
                                    unset($dados['newsletter']);

                                    $newN = new Create();
                                    $newN->ExeCreate(PREFIX."newsletter", $dados);

                                    if($newN->getResult()){
                                        echo "<label class='msg-erro' style='color:#2e9421; display:block;'>E-mail cadastrado com sucesso. Obrigado!</label>";
                                    }else{  
                                        echo "<label class='msg-erro' style='color:#e31412; display:block;'>Erro ao cadastrar o e-mail. Tente  mais tarde.</label>";
                                    }
                               	endif;
                            }
                        else:
                            echo "<label class='msg-erro' style='display:none;'></label>";
                        endif;
                    ?>

		        </div>
		        <a href="https://www.facebook.com/santacasadelorena/">
					<div class='facebook'>
						<img src='<?php echo ROOT; ?>resources/img/icon-facebook-footer.svg' class='icons'>
					</div>
					<p>
						Curta nossa página!<br>
						/santacasadelorena
					</p>
				</a>
				<a href="https://www.instagram.com/santacasadelorena/">
					<div class='facebook'>
						<img style="padding-top: 5px; padding-left: 5px;" src='<?php echo ROOT; ?>resources/img/icon-instagram-footer.svg' class='icons'>
					</div>
					<p>
						Siga nossa página!<br>
						@santacasadelorena
					</p>
				</a>
			</form>
		</div>

		<div class="clear"></div> 

		<div class="col-md-12"><center><p>Copyright © por <a href='http://pentaxial.com.br/'>Pentaxial</a>. Todos os direitos reservados.</p></center></div>

	</div>
</section>

<!-- jQuery UI 1.11.4 -->
<script src="<?php echo ROOT; ?>resources/plugins/jQueryUI/jquery-ui.min.js"></script>
<script>
	$.widget.bridge('uibutton', $.ui.button);
</script>

<script src='https://www.google.com/recaptcha/api.js'></script>

<script type="text/javascript">

	$(document).ready(function(){
	  
	  	//$(".banner-principal").owlCarousel();

	  	$('.owl-banner').owlCarousel({
	        loop:true,
	        margin:10,
	        nav:false,
	        items: 1
	    });

	    $('.owl-atendimento').owlCarousel({
	        center: true,
	        items:4,
	        loop:true,
	        margin:1
	    });

	  	$('.carousel-instalacoes').owlCarousel({
		    loop:false,
		    margin:30,
		    responsive:{
		        0:{
		            items:1,
		        },
		        480:{
		            items:1,
		            stagePadding: 60	
		        },
		        560:{
		            items:1,
		            stagePadding: 100
		        },
		        650:{
		            items:2,
		        },
		        740:{
		            items:2,
		        },
		        840:{
		            items:2,
		            stagePadding: 60
		        },
		        900:{
		            items:3,
		        },
		        1170:{
		            items:4
		        }
		    }
		});

		$('.carousel-convenios').owlCarousel({
		    loop:true,
		    margin:30,
		    responsive:{
		        0:{
		            items:2,
		            margin:5
		        },
		        480:{
		            items:4
		        },
		        740:{
		            items:4
		        }
		    }
		});

		$('.carousel-noticias').owlCarousel({
		    loop:true,
		    margin:30,
		    touchDrag: true,
        	mouseDrag: true,
		    responsive:{
		        0:{
		            items:1
		        },
		        740:{
		            items:2,
		            margin:3
		        },
		        1170:{
		            items:3,
		            margin:1,
		            nav: false,
		            loop: false,
		            touchDrag: false,
        			mouseDrag: false
		        }
		    }
		});

		$(function(){
			let url_atual = window.location.href;
			let url = getFinalUrl(url_atual);

			if(url[0] == ""){
				$(".menu-principal #menu-item-home").addClass("active");
			}
			else{
				$(".menu-principal #menu-item-"+url[0]).addClass("active");
			}
		});

		function getFinalUrl(url_atual){
			let url = [];
			let c = 0, n = -1;
			for (var i = 0; i < url_atual.length ; i++) {
				let key = url_atual.charAt(i);
				if(key == "/"){
					c++;
					if(c > 3){
						n++;
						url.push("");
					}
				} else {
					if(n > -1) url[n] += key; 
				}
			}
			//console.log(url);
			return url;
		}

	});

	//Validação de e-mail
    function validaEmail(email){
        var exclude=/[^@\-\.\w]|^[_@\.\-]|[\._\-]{2}|[@\.]{2}|(@)[^@]*\1/;
        var check=/@[\w\-]+\./;
        var checkend=/\.[a-zA-Z]{2,3}$/;
        
        if(((email.search(exclude) != -1)||(email.search(check)) == -1)||(email.search(checkend) == -1)){
            return false;}
        else{
            return true;
        }
    }
	
	$("#form_news").on('submit', function (e) {
        e.preventDefault();

		$(".form-group").removeClass("has-warning").find(".msg-erro").attr("style","").text("");

        if($('#newsletter').val() == "" || !validaEmail($('#newsletter').val())){

            $('#newsletter').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um e-mail válido");
       	}else{
                
            $(this).off('submit').submit();  
        }
	});

</script>
</body>
</html>