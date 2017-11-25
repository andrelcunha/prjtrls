<?php
include_once('../autoload.php');

header('Content-Type: application/json');

$mensagens  = array();

//if (isset($_POST['token']))
if (isset($_GET['token']))
{
//    $token          = $_POST['token'];
    $token          = $_GET['token'];
}
else
{
    $mensagens[]    = 'Token não informado';
    $token          = '';
}

//if (isset($_POST['codigo']))
if (isset($_GET['codigo']))
{
//    $codigo         = $_POST['codigo'];
    $codigo         = $_GET['codigo'];
}
else
{
    $mensagens[]    = 'Código da missão não informado';
    $codigo         = '';
}

if (count($mensagens) == 0)
{
    $missao         = new lib\ws\jsmissao();
}
else
{
    $missao         = new lib\ws\jsmissaoerros();
}

if (count($mensagens) == 0)
{
    //tem que fazer os testes se o token está ativo
    $missaoobj  = new lib\missao();
    $missaoobj->SelecionarComOutrosCadastros($codigo);

    if ($missaoobj->id != null)
    {
        $missao->ano        = $missaoobj->ano;
        $missao->semestre   = $missaoobj->semestre;
        $missao->titulo     = $missaoobj->titulo;
        $missao->descricao  = $missaoobj->descricao;
        
        $missao->eixos      = array();

        foreach ($missaoobj->eixos as $eixoobj)
        {
            $missao->eixos[]                    = new lib\ws\jsmissaoeixo();
            $poseixo                            = count($missao->eixos) - 1;

            $eixoobj2                           = new lib\eixo();
            $eixoobj2->Selecionar($eixoobj->eixo);

            $missao->eixos[$poseixo]->eixo      = strtoupper($eixoobj2->nome);
            $missao->eixos[$poseixo]->pontos    = $eixoobj->pontos;
        }

        $missao->npc        = array();

        foreach ($missaoobj->falasnpc as $falaobj)
        {
            $missao->npc[]                      = new lib\ws\jsmissaonpc();
            $posnpc                             = count($missao->npc) - 1;

            $npcobj                             = new lib\npc();
            $npcobj->Selecionar($falaobj->npc);

            $missao->npc[$posnpc]->personagem   = $npcobj->chave;
            $missao->npc[$posnpc]->humor        = $falaobj->humor;
            $missao->npc[$posnpc]->texto        = $falaobj->texto;
        }
    }
    else
    {
        $missao                         = new lib\ws\jsmissaoerros();
        $missao->erros                  = array();
        $missao->erros[0]               = new lib\ws\jsmissaoerro();
        $missao->erros[0]->mensagem     = 'Código da missão não existe';
    }
}
else
{
    $missao->erros  = array();
    $i              = 0;
    
    foreach ($mensagens as $mensagem)
    {
        $missao->erros[$i]              = new lib\ws\jsmissaoerro();
        $missao->erros[$i]->mensagem    = $mensagem;
        $i++;
    }
}

$missao->token  = $token;
$missao->codigo = $codigo;

echo(json_encode($missao));
?>