<?php
namespace Jb\Core\Generic;

interface ListInterface
{
    /**
     * insere elemento na lista
     *
     * @param  Element $elm
     * @return ListInterface
     **/
    public function push(Element $elm);

    /**
     * remove elemento da lista, em caso de sucesso, retorna o elemento removido
     * ou null em caso de falha
     *
     * @param Element $elm
     * @return Element
     **/
    public function pop(Element $elm);

    /**
     * retorna true se a lista estiver vazia
     *
     * @return boolean
     **/
    public function empty();
}
