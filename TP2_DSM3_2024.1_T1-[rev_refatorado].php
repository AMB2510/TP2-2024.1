<?php
// T1 - TP2 - DSM3 2024.1 - REVISÃO de Implementação e Refatoração com Padrões FACTORY PATTERN, SINGLETON PATTERN e STRATEGY PATTERN.
// Script de Controle de Vendas de loja de produtos escolares com conceito POO, usando diagrama UML sem framework.


//SINGLETON para gerenciar dados dos produtos, clientes e vendas.
class DataManager {
    private static $instance;
    private $clientesCad;
    private $produtosCad;
    private $vendasCad;

    private function __construct() {
        $this->clientesCad = array();
        $this->produtosCad = array();
        $this->vendasCad = array();
    }

    public static function getInstance() {
        if (self::$instance === null) {            //se a instância não foi criada, um novo Gerenciador de Dados será feito.
            self::$instance = new DataManager();
        }
        return self::$instance;
    }

    public function addCliente(Cliente $cliente) {
        $this->clientesCad[] = $cliente;
    }

    public function getClientes() {
        return $this->clientesCad;
    }

    public function addProduto(Produto $produto) {
        $this->produtosCad[] = $produto;
    }

    public function getProdutos() {
        return $this->produtosCad;
    }

    public function addVenda(Venda $venda) {
        $this->vendasCad[] = $venda;
    }

    public function getVendas() {
        return $this->vendasCad;
    }
}


//STRATEGY para diferentes estratégias de preços.
interface PricingStrategy {
    public function calculateTotal($quantity, $price, $discount);
}

class StandardPricingStrategy implements PricingStrategy {
    public function calculateTotal($quantity, $price, $discount) {
        return $quantity * $price * (1 - $discount);
    }
}


//FACTORY para criação de objetos Cliente e Produto
class Factory {
    public static function createCliente($nome, $endereco, $telefone, $nascimento, $status, $email, $genero) {
        return new Cliente($nome, $endereco, $telefone, $nascimento, $status, $email, $genero);
    }

    public static function createProduto($descricao, $estoque, $preco, $medida) {
        return new Produto($descricao, $estoque, $preco, $medida);
    }

    public static function createVenda($cliente, $idCliente) {
        return new Venda($cliente, $idCliente);
    }

    public static function createItem() {
        return new Item();
    }
}


class Cliente {
    protected $nome;
    protected $endereco;
    protected $telefone;
    protected $nascimento;
    protected $status;
    protected $email;
    protected $genero;

    private static $contador = 0;  //para gerar um novo ID a cada novo cliente
    protected $idCliente;          //gerado com a propriedade estática acima
    protected $vendas;             //array que receberá todas as vendas do cliente

    function __construct($nome, $endereco, $telefone, $nascimento, $status, $email, $genero) {
        $this->nome = $nome;
        $this->endereco = $endereco;
        $this->telefone = $telefone;
        $this->nascimento = $nascimento;
        $this->status = $status;
        $this->email = $email;
        $this->genero = $genero;
        $this->vendas = array();                   //inicia como array vazio
        self::$contador++;                         //contador é incrementado
        $this->idCliente = 'C' . self::$contador;  //ID recebe o novo valor do contador
    }

    function getIdCliente() {
        return $this->idCliente;
    }

    function addVenda($venda) {
        $this->vendas[] = $venda;
    }

    function dadosCliente() {
        echo "ID: " . $this->idCliente . "\n";
        echo "Nome: " . $this->nome . "\n";
        echo "Endereço: " . $this->endereco . "\n";
        echo "Telefone: " . $this->telefone . "\n";
        echo "Nascimento: " . $this->nascimento . "\n";
        echo "Status: " . $this->status . "\n";
        echo "Email: " . $this->email . "\n";
        echo "Gênero: " . $this->genero . "\n";
        echo "------------------------------------\n";
    }
}


class Produto {
    protected $descricao;
    protected $estoque;
    protected $preco;
    protected $medida;

    private static $contador = 0;  //variável estática para manter o contador de produtos
    protected $idProduto;          //gerado com a propriedade estática acima

    function __construct($descricao, $estoque, $preco, $medida) {
        $this->descricao = $descricao;
        $this->estoque = $estoque;
        $this->preco = $preco;
        $this->medida = $medida;
        self::$contador++;                         //contador é incrementado
        $this->idProduto = 'P' . self::$contador;  //ID recebe o novo valor do contador
    }

    function getIdProduto() {
        return $this->idProduto;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getEstoque() {
        return $this->estoque;
    }

    function getPreco() {
        return $this->preco;
    }

    function getMedida() {
        return $this->medida;
    }

    function dadosProduto() {
        echo "------------------------------------\n";
        echo "ID: " . $this->idProduto . "\n";
        echo "Descrição: " . $this->descricao . "\n";
        echo "Estoque: " . $this->estoque . "\n";
        echo "Preço: " . $this->preco . "\n";
        echo "Medida: " . $this->medida . "\n";
    }
}


class Venda {
    protected $cliente;            //recebe o cliente cadastrado
    protected $itens;              //array para guardar os produtos vendidos [todo]
    private static $contador = 0;  //para gerar uma nova ID a cada nova venda
    protected $idVenda;            //recebe o ID pela propriedade contador
    protected $data;
    protected $valorTot;

    public function __construct(Cliente $cliente, $idCliente) {
        $this->cliente = $cliente;
        $this->idCliente = $idCliente;
        $this->data = date('d-m-Y H:i:s');

        $this->itens = array();                    //cria o array para guardar os produtos vendidos
        self::$contador++;                         //contador é incrementado
        $this->idVenda = 'PED' . self::$contador;  //ID recebe o novo valor do contador
    }

    public function addItem(Item $item) {
        $this->itens[] = $item;
    }

    //calcula o total geral da venda [soma todos os itens vendidos]
    public function obterTotal() {
        $total = 0;
        foreach ($this->itens as $item) {
            $total += $item->getTotal();
        }
        $this->valorTot = $total;  //armazena o total na propriedade $valorTot
        return $total;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function getIdCliente() {
        return $this->idCliente;
    }

    public function getIdVenda() {
        return $this->idVenda;
    }

    //exibe as vendas
    public function dadosVenda() {
        echo "------------------------------------\n";
        echo "Id Pedido: " . $this->idVenda . "\n";
        echo "Data: " . $this->data . "\n";
        echo "Cliente: " . $this->idCliente . "\n";
        echo "Itens:\n";
        foreach ($this->itens as $item) {
            $item->dadosItem();
        }
        echo "Total: R$ " . $this->valorTot . "\n";
    }
}


class Item {
    protected $produto;
    protected $quantidade;
    protected $preco;
    protected $desconto;
    protected $total;

    //associa o produto ao método addItem
    public function setProduto(Produto $produto) {
        $this->produto = $produto;
    }

    //setters e getters
    public function setPreco($preco) {
        $this->preco = $preco;
    }
    
    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }

    public function setDesconto($desconto) {
        $this->desconto = $desconto;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function getTotal() {
        return $this->total;
    }

    public function dadosItem() {
        echo "------------------------------------\n";
        echo "Produto: " . $this->produto->getDescricao() . "\n";
        echo "Preço: " . $this->preco . "\n";
        echo "Quantidade: " . $this->quantidade . "\n";
        echo "Desconto %: " . $this->desconto . "\n";
        echo "Total: " . $this->total . "\n";
    }
}





//Menu Principal do Programa
$dataManager = DataManager::getInstance();

do {
    echo "------------------------------------\n";
    echo "1- Cadastrar Produto\n";
    echo "2- Listar Produtos\n";
    echo "3- Cadastrar Cliente\n";
    echo "4- Listar Clientes\n";
    echo "5- Cadastrar Venda\n";
    echo "6- Listar Vendas\n";
    echo "7- Imprimir pedido\n";
    echo "0- Sair\n";
    echo "------------------------------------\n";

    //recebe a opção do menu
    $menu = intval(fgets(STDIN)); //conversão para valor inteiro


    //executa o programa de acordo com o menu
    switch ($menu) {

        case 1:   //solicita os dados ao usuário
            echo "------------------------------------\n";
            $descricao = readline("Descrição do produto: ");
            $estoque = readline("Estoque: ");
            $preco = readline("Preço: ");
            $medida = readline("Unidade de medida [un]/[pc]: ");
            echo "------------------------------------\n";

            //cria um objeto de Produto (novo produto)
            $produto = Factory::createProduto($descricao, $estoque, $preco, $medida);
            $dataManager->addProduto($produto);
            break;

        case 2:   //confere se já existem produtos cadastrados
            $produtos = $dataManager->getProdutos();
            if (!empty($produtos)) {
                echo "------------------------------------\n";
                echo "PRODUTOS CADASTRADOS: \n";

                //percorre o array de produtos e aciona o método que lista os produtos cadastrado
                foreach ($produtos as $itemProduto) {
                    $itemProduto->dadosProduto();
                }
            } else {
                echo "------------------------------------\n";
                echo "NENHUM PRODUTO CADASTRADO! \n";
            }
            break;

        case 3:   //solicita os dados ao usuário
            echo "------------------------------------\n";
            $nome = readline("Nome: ");
            $endereco = readline("Endereço: ");
            $telefone = readline("Telefone: ");
            $nascimento = readline("Data de Nascimento [dd/mm/aaaa]: ");
            $status = readline("Status [A]/[I]: ");
            $email = readline("Email: ");
            $genero = readline("Gênero [F]/[M]: ");
            echo "------------------------------------\n";

            //cria um objeto de Cliente (novo cliente)
            $cliente = Factory::createCliente($nome, $endereco, $telefone, $nascimento, $status, $email, $genero);
            $dataManager->addCliente($cliente);
            break;

        case 4:   //confere se já existem clientes cadastrados
            $clientes = $dataManager->getClientes();
            if (!empty($clientes)) {
                echo "------------------------------------\n";
                echo "CLIENTES CADASTRADOS: \n";

                //percorre o array de clientes e aciona o método que lista os clientes cadastrados
                foreach ($clientes as $cliente) {
                    $cliente->dadosCliente();
                }
            } else {
                echo "------------------------------------\n";
                echo "NENHUM CLIENTE CADASTRADO! \n";
            }
            break;

        case 5:   //confere se já existem clientes cadastrados
            echo "------------------------------------\n";
            $idCliente = readline("Id do cliente: ");
            $clienteEncontrado = false;

            //percorre o array de clientes em busca do ID
            foreach ($dataManager->getClientes() as $clienteDisponivel) {
                if ($clienteDisponivel->getIdCliente() === $idCliente) {
                    $clienteEncontrado = true;
                    break;
                }
            }

            if (!$clienteEncontrado) {
                echo "Cliente não cadastrado!\n";
            } else {
                //cria um novo objeto venda
                $venda = Factory::createVenda($clienteDisponivel, $idCliente);

                //loop para adicionar itens
                do {
                    //variável para validar se o produto existe
                    $produtoEncontrado = false;

                    //solicita o produto a ser adicionado
                    $idProduto = readline("ID do produto: ");

                    //percorre o array de produtos cadastrados para localizar o produto solicitado
                    foreach ($dataManager->getProdutos() as $produtoDisponivel) {
                        if ($produtoDisponivel->getIdProduto() == $idProduto) {
                            $produtoEncontrado = true;   //produto foi encontrado
                            
                            //solicita ao usuário quantidade e desconto
                            $quantidade = readline("Quantidade: ");
                            $desconto = readline("Desconto [0.1 = 10%]: ");

                            $item = Factory::createItem();
                            $item->setProduto($produtoDisponivel);
                            $item->setQuantidade($quantidade);
                            $item->setDesconto($desconto);

                            $strategy = new StandardPricingStrategy();
                            $item->setTotal($strategy->calculateTotal($quantidade, $produtoDisponivel->getPreco(), $desconto));

                            //chama o método addItem e guarda no array
                            $venda->addItem($item);
                        }
                    }

                    //caso o produto não seja encontrado
                    if (!$produtoEncontrado) {
                        echo "Produto não cadastrado!\n";
                    }

                    echo "------------------------------------\n";
                    echo "1- Adicionar outro item \n";
                    echo "2- Finalizar Pedido \n";
                    echo "0- Cancelar Venda \n";
                    $m = intval(fgets(STDIN));   //conversão para valor inteiro

                    if ($m == 2) {
                        //chama o método para calcular o total da venda
                        $valorTot = $venda->obterTotal();
                        echo "Total da venda: R$" . $valorTot . "\n";

                        //guarda todas as vendas finalizadas/cadastradas
                        $dataManager->addVenda($venda);
                    }

                    if ($m == 0) {
                        echo "Venda cancelada!\n";
                        unset($venda);           //volta ao valor anterior
                        break;
                    }

                } while ($m != 0 && $m != 2);    //loop é interrompido caso 0 ou 2
            }
            break;

        case 6:   //confere se já existem vendas cadastrados
            $vendas = $dataManager->getVendas();
            if (!empty($vendas)) {
                echo "------------------------------------\n";
                $idCliente = readline("Id do cliente: \n");
                echo "\n VENDAS REGISTRADAS:\n";
                echo "------------------------------------\n";

                //percorre o array de vendas e aciona o método que lista as vendas cadastradas
                foreach ($vendas as $venda) {
                    if ($venda->getCliente()->getIdCliente() === $idCliente) {
                        $venda->dadosVenda();
                    }
                }
            } else {
                echo "------------------------------------\n";
                echo "NENHUMA VENDA REGISTRADA! \n";
            }
            break;

        case 7:   //solicita o código do pedido
            echo "------------------------------------\n";
            $idVenda = readline("Id do Pedido: \n");

            //localiza o pedido para exibição dos dados da venda
            foreach ($dataManager->getVendas() as $venda) {
                if ($venda->getIdVenda() === $idVenda) {
                    echo "--------IMPRESSÃO DO PEDIDO--------\n";
                    $venda->dadosVenda();

                    //localiza o cliente do pedido para exibição dos dados
                    foreach ($dataManager->getClientes() as $cliente) {
                        if ($cliente->getIdCliente() === $venda->getIdCliente()) {
                            $cliente->dadosCliente();
                        }
                    }
                }
            }
            break;

        case 0:
            echo "Encerrando o programa...";
            break;

        default:
            echo "Entrada inválida!";
            break;
    }

} while ($menu != 0);

?>