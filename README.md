# CRUD de alunos Back-End

Funcionalidades implementadas

- Sistema de login e autenticação
- Cadastro de alunos
- Edição das informações dos alunos
- Remoção de alunos
- Visualização detalhada de um aluno
- Tabela com todos os alunos cadastrados

## Instruções para configuração e execução

Depois de baixar o projeto execute:

`composer update`

Após configurar o banco de dados apropriamente no .env faça as migrações com:

`php spark migrate`

E por fim popule as tabelas:

`php spark db:seed`

`Users`

O projeto vai junto com as rotas do insomnia, também é recomendado utilizar a distribuição apache XAMPP
