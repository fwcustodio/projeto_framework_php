# Construção de um framework PHP, estilo Zend, para uma empresa de desenvolvimento de sites

Criei este projeto começo de 2012, 1 ano após iniciar minha carreira na área de programação e desenvolvimento de software.
Tinha ele guardado aqui, e como se trata de um projeto muito interessante, com bastante codificação e estrutura de dados, resolvi tentar roda-lo e disponibiliza-lo aqui no GIT.

- Linguagem PHP
- Versão 4.5
- Docker

# Resolução do problema

Na época a empresa precisava de uma forma de componentizar melhor os módulos utilizados nos sites, que geralmente utilzavam módulos semelhantes, como: Noticias, Contato, Sobre e etc...
Era dificl compartilhar estes modulos sem fazer grandes alterações e inserções nos projetos.

Dessa forma, com influencia de frameworks, como Zend ,Symfony e Cake, contrui este framework, integrado com o framework existente da emrpesa.

Além disso gerei um gerador de módulo, "Engine Site" ,com uma interface visual, que criava os arquivos com base na seleção. O Engine site ja utilizava o framework novo.

Aqui no repositorio tem o Framework novo e o Engine Site, embora incompletos.

# Execução

Por se tratar de um projeto antigo que usa PHP 5, resolvi utilizar o Docker com uma imagem com PHP, dessa forma é possivel de ser executado em container.
Basta rodar o arquivo .bat na raiz do repositorio run_docker.bat .

```sh
run_docker.bat
```
ou

```sh
docker build -t my-php-app .
docker run -d -p 80:80 --name my-running-app my-php-app
```

Para acessar a aplicação, basta ir ao navegador e acessar

```sh
http://localhost/engine_site/
```

![image](https://github.com/user-attachments/assets/d54c3d46-ccf8-4994-a028-f67b4cf139d0)

O projeto esta com arquivos de js e css faltando da época que foi construido, dessa forma nao funciona totalmente. Então decidi forçar uma geração de módulo ja na entrada da primeira tela da aplicação.

No proprio engine_site/index.php é executado o código para a criação de um novo módulo com todas as opções disponíveis. Isso era feito via interface antigamente.

```sh
require_once('../sis/framework/config.conf.php');  ConfigSIS::Conf(); $ConfigSIS = new ConfigSIS(); $ConfigSIS->load();  $EngineSiteController = EngineSiteController::getInstancia();

$Controller = EngineSiteController::getInstancia();
$DadosTeste = ['PastaSistema' => 'engine_site', 'NomeModulo' => 'teste', 'Layout' => 'padrao', 'checkBoxArquivos' => 'on', 'checkBoxAjax' => 'on', 'checkBoxController' => 'on', 'checkBoxCss' => 'on', 'checkBoxForm' => 'on', 'checkBoxJs' => 'on', 'checkBoxModel' => 'on', 'checkBoxViews' => 'on', 'Views' => 'teste', 'checkBoxIndex' => 'on', 'checkBoxOpcaoLayout' => 'on'];
$Resp = $Controller->gerarArquivos($DadosTeste);
echo $Resp;
```

Os arquivos do módulo são gerados no caminho: engine_site/ + o nome do módulo "TESTE"
Como a aplicação está dentro do container Docker é necessário executar um comando para retirar a pasta do módulo para o computador. Basta executar o bat

```sh
run_copiar_modulo.bat
```
ou 

```sh
docker cp my-running-app:/var/www/html/engine_site/teste C:/
```

