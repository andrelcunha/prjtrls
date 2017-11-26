<?php
namespace lib;

class pergunta
{
    const PERGUNTA_ID           = 0;
    const PERGUNTA_ENUNCIADO    = 1;
    const PERGUNTA_CODIGO       = 2;
    const EIXO_ID               = 3;
    const PERGUNTA_ATIVO        = 4;

    public $id;
    public $enunciado;
    public $codigo;
    public $eixo;
    public $ativo;
    public $certa;
    public $erradas = array();

    public function ListarRegistros($pagina)
    {
        $matriz = array();
        
        $sql    = 'SELECT perguntaId, perguntaEnunciado, perguntaCodigo, eixoId, perguntaAtivo ' .
                  'FROM perguntas ' .
                  'ORDER BY perguntaEnunciado';

        $db     = new bancodados();
        $res    = $db->SelecaoSimples($sql);

        if ($res !== FALSE)
        {
            if (count($res) > 0)
            {
                foreach ($res as $perg)
                {
                    $obj                = new pergunta();
                    $obj->id            = $perg[self::PERGUNTA_ID];
                    $obj->enunciado     = $perg[self::PERGUNTA_ENUNCIADO];
                    $obj->codigo        = $perg[self::PERGUNTA_CODIGO];
                    $obj->eixo          = $perg[self::EIXO_ID];
                    $obj->ativo         = $perg[self::PERGUNTA_ATIVO];

                    array_push($matriz, $obj);
                }
            }
        }

        return $matriz;
    }

    public function ListarPorCategoria($eixo)
    {
        $matriz = array();
        
        $sql    = 'SELECT perguntaId, perguntaEnunciado, perguntaCodigo, eixoId, perguntaAtivo ' .
                  'FROM perguntas ' .
                  "WHERE eixoId = '$eixo' " .
                  'ORDER BY perguntaEnunciado';

        $db     = new bancodados();
        $res    = $db->SelecaoSimples($sql);

        if ($res !== FALSE)
        {
            if (count($res) > 0)
            {
                foreach ($res as $perg)
                {
                    $obj                = new pergunta();
                    $obj->id            = $perg[self::PERGUNTA_ID];
                    $obj->enunciado     = $perg[self::PERGUNTA_ENUNCIADO];
                    $obj->codigo        = $perg[self::PERGUNTA_CODIGO];
                    $obj->eixo          = $perg[self::EIXO_ID];
                    $obj->ativo         = $perg[self::PERGUNTA_ATIVO];

                    $certa              = new resposta();
                    $certa->SelecionarCerta($obj->id);
                    $this->certa        = $certa;

                    $erradas            = new resposta();
                    $this->erradas      = $erradas->SelecionarErradas($obj->id);

                    array_push($matriz, $obj);
                }
            }
        }

        return $matriz;
    }

    public function Salvar()
    {
        $erro       = -1;

        $sql        = 'INSERT INTO perguntas ' .
                      '(perguntaId, perguntaEnunciado, perguntaCodigo, perguntaAtivo) ' . 
                      "VALUES ('{ID}', '$this->enunciado', '$this->codigo', $this->ativo)";

        $db         = new bancodados();
        $this->id   = $db->ExecutarRetornaId($sql);

        if ($this->id != null)
        {
            if ($this->certa != null)
            {
                $rescerta   = $this->certa->Salvar($this->id);

                if (!$rescerta)
                {
                    $erro   = 2;
                }
            }

            if ($this->erradas != null)
            {
                foreach ($this->erradas as $errada)
                {
                    $errada->Salvar($this->id);

                    if (!$rescerta)
                    {
                        $erro   += 4;
                    }
                }
            }
        }
        else
        {
            $erro   = 1;
        }

        return $erro;
    }
}
?>