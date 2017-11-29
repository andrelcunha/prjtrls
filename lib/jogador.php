<?php
namespace lib;

class jogador
{
    const JOGADOR_ID        = 0;
    const JOGADOR_DINHEIRO  = 1;
    const JOGADOR_PONTOS    = 2;
    const JOGADOR_CABELO    = 3;
    const JOGADOR_PELE      = 4;
    const JOGADOR_SEXO      = 5;
    const JOGADOR_ANO       = 6;

    public $id;
    public $dinheiro;
    public $pontos;
    public $cabelo;
    public $pele;
    public $sexo;
    public $ano;
    public $eixos;
    public $missoes;

    public function Selecionar($id)
    {
        $sql    = 'SELECT alunoId, jogadorDinheiro, jogadorPontuacao, jogadorCabelo, jogadorPele, jogadorSexo, jogadorAno ' .
                  'FROM jogadores ' .
                  "WHERE alunoId = '$id'";

        $db     = new bancodados();
        $res    = $db->SelecaoSimples($sql);

        if ($res !== false)
        {
            if (count($res) > 0)
            {
                $jogador        = $res[0];

                $this->id               = $jogador[self::JOGADOR_ID];
                $this->dinheiro         = $jogador[self::JOGADOR_DINHEIRO];
                $this->pontos           = $jogador[self::JOGADOR_PONTOS];
                $this->cabelo           = $jogador[self::JOGADOR_CABELO];
                $this->pele             = $jogador[self::JOGADOR_PELE];
                $this->sexo             = $jogador[self::JOGADOR_SEXO];
                $this->ano              = $jogador[self::JOGADOR_ANO];
            }
        }
    }
}
?>