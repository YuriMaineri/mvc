<?php


namespace Alura\Cursos\Controller;


use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Alura\Cursos\Infra\EntityManagerCreator;

class Persistencia implements InterfaceControladorRequisicao
{
    use FlashMessageTrait;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManger;

    public function __construct()
    {
        $this->entityManger = (new EntityManagerCreator())->getEntityManager();
    }

    public function processaRequisicao(): void
    {
        $descricao = filter_input(
            INPUT_POST,
            'descricao',
            FILTER_SANITIZE_STRING
        );

        $curso = new Curso();
        $curso->setDescricao($descricao);

        $id = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        $tipo = 'success';
        if (!is_null($id) && $id !== false) {
            $curso->setId($id);
            $this->entityManger->merge($curso);
            $this->defineMensagem($tipo, 'Curso '. $curso->getDescricao() .' atualizado com sucesso');
        } else {
            $this->entityManger->persist($curso);
            $this->defineMensagem($tipo, 'Curso '. $curso->getDescricao() .' inserido com sucesso');
        }

        $this->entityManger->flush();



        header('Location: /listar-cursos', true, 302);
    }
}