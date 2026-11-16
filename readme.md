# 🏋️‍♂️ FitLife Studio — Sistema de Gestão para Estúdio de Treino

> Solução computacional integrada (Web em PHP + Desktop/API em C# + Banco de Dados MySQL) desenvolvida para substituir o controle manual em planilhas e papel de estúdios de treino personalizado (musculação, treino funcional e pilates).

---

## 📋 Sobre o Projeto

O **FitLife Studio** é um sistema de gestão desenvolvido como **Projeto de Extensão em Programação** na **Universidade Nove de Julho (UNINOVE)**, sob orientação do(a) **Prof(a). Marcio Klein** (2026).

O projeto aplica de forma integrada os conhecimentos das disciplinas de **Modelagem de Sistemas (UML)**, **Gestão de Projetos (PMBOK)**, **DevOps (Git/GitHub)**, **Desenvolvimento em Banco de Dados (MySQL)**, **Programação Back-End (PHP)** e **Programação Visual (C#)**.

### 🎯 Objetivos

* **Objetivo Geral:** Informatizar o cadastro e o controle de alunos, planos, professores, aulas, matrículas e agendamentos do estúdio FitLife Studio, reduzindo erros de agenda e perda de informações.
* **Objetivos Específicos:**
  * Modelar o sistema utilizando UML (Casos de Uso, Classes e Atividades) e DER.
  * Aplicar práticas de gestão de projetos baseadas no PMBOK (Escopo, EAP e Análise de Riscos).
  * Estruturar o versionamento de código com Git seguindo fluxo de branches (`main`, `dev` e `feature/*`).
  * Implementar um banco de dados relacional MySQL compartilhado por duas aplicações distintas.
  * Desenvolver uma plataforma Web em PHP (com PDO e *prepared statements*) e uma aplicação/módulo em C# (.NET) integrados à mesma base de dados.

---

## ✨ Funcionalidades e Requisitos Atendidos

* [x] **RF01 — Cadastro de Alunos:** Criação, listagem, edição e exclusão (CRUD) de alunos.
* [x] **RF02 — Cadastro de Planos:** Gestão completa de planos (tipo, valor e duração em meses).
* [x] **RF03 — Cadastro de Aulas/Turmas:** Gerenciamento de modalidades (musculação, funcional, pilates), horários, dias da semana e professores responsáveis.
* [x] **RF04 — Matrícula de Alunos:** Associação de alunos a planos ativos.
* [x] **RF05 — Agendamento de Aulas:** Reserva de vagas em aulas com verificação automática de disponibilidade.
* [x] **RF06 — Consulta de Frequência:** Acompanhamento de presença e histórico do aluno.
* [x] **RNF01 & RNF02 — Usabilidade e Segurança:** Interface intuitiva com controle de acesso por perfis (**Administrador**, **Professor** e **Aluno**) e consultas protegidas contra SQL Injection via *prepared statements* (PDO no PHP e `MySqlCommand` parametrizado no C#).

---

## 🛠️ Tecnologias Utilizadas

* **Sistema Web (Back-end):** PHP 8+ com PDO (*PHP Data Objects*)
* **Sistema Desktop / API:** C# / .NET (`Windows Forms` / `MySql.Data` / `api-csharp/`)
* **Front-end:** HTML5, CSS3, Bootstrap e JavaScript
* **Banco de Dados:** MySQL 8.0 / MariaDB (6 entidades: `aluno`, `professor`, `plano`, `aula`, `matricula` e `agendamento`)
* **DevOps & Ferramentas:** Git, GitHub, XAMPP, Visual Studio / VS Code

---

## 📂 Estrutura do Repositório

```text
fitlife/
├── admin/                  # Painel administrativo e gestão geral do estúdio
├── aluno/                  # Área restrita do aluno (agendamentos e frequência)
├── professor/              # Área restrita do professor (turmas e horários)
├── api-csharp/             # Módulo / Projeto em C# (.NET) integrado ao MySQL
├── assets/                 # Imagens e recursos visuais estáticos
├── config/                 # Configuração de conexão PDO com o banco (conexao.php)
├── css/                    # Folhas de estilo personalizadas
├── includes/               # Componentes compartilhados (header, footer, menus)
├── js/                     # Scripts de interatividade front-end
├── uploads/                # Diretório de uploads (fotos de perfil)
├── aulas.php               # Catálogo e agenda pública de aulas
├── cadastro.php            # Cadastro de novos usuários
├── database.sql            # Script SQL de criação das tabelas e dados iniciais
├── index.php               # Página inicial da plataforma FitLife Studio
├── login.php               # Autenticação de usuários
├── logout.php              # Encerramento de sessão
├── pagamento.php           # Fluxo de contratação/pagamento de planos
├── planos.php              # Listagem pública de planos disponíveis
├── professores.php         # Listagem pública de instrutores
└── README.md               # Documentação do projeto
```

---

## 🚀 Como Executar o Projeto

### Pré-requisitos

* Servidor local com **PHP** e **MySQL** (ex: [XAMPP](https://www.apachefriends.org/))
* [.NET SDK](https://dotnet.microsoft.com/) / Visual Studio (para o módulo em C#)
* [Git](https://git-scm.com/)

### Passo a passo

1. **Clone o repositório na pasta pública do seu servidor local** (ex: `htdocs` no XAMPP):
   ```bash
   git clone https://github.com/HelderRochaFk/FitLife-.git fitlife
   ```

2. **Crie e importe o banco de dados:**
   * Inicie os módulos **Apache** e **MySQL** no painel do XAMPP.
   * Acesse o **phpMyAdmin** (`http://localhost/phpmyadmin`).
   * Crie um banco de dados (ex: `fitlife` ou `fitlife_studio`) e importe o arquivo `database.sql` localizado na raiz do projeto.

3. **Configure a conexão com o banco de dados:**
   * Abra `config/conexao.php` e ajuste os parâmetros `$host`, `$dbname`, `$user` e `$pass` conforme o seu ambiente local.

4. **Acesse o Sistema Web no navegador:**
   ```text
   http://localhost/fitlife/
   ```

5. **Execute o módulo C# (.NET):**
   * Abra o projeto contido em `api-csharp/` (ou a solução Desktop) no Visual Studio ou execute via terminal:
     ```bash
     cd fitlife/api-csharp
     dotnet run
     ```

---

## 👥 Equipe e Divisão de Papéis

Projeto desenvolvido pelos discentes da **Universidade Nove de Julho (UNINOVE)**:

| Integrante | RA | Papel / Responsabilidades |
| :--- | :--- | :--- |
| **Helder Rocha de Lima** | `425108037` | Gerenciamento do Projeto & DevOps (Git/GitHub e ambiente) |
| **Jaqueline Vieira de Freitas** | `425101645` | Gerenciamento do Projeto & DevOps (Git/GitHub e ambiente) |
| **Carolina dos Santos Araujo** | `425203243` | Análise de Requisitos e Modelagem UML |
| **João Gabriel Campos Ferreira** | `425106302` | Análise de Requisitos e Modelagem UML |
| **Vinicius Rosanti Vallone** | `425102322` | Modelagem (DER) e Implementação do Banco de Dados MySQL |
| **Cauã Afonseca de Oliveira** | `425106030` | Desenvolvimento Back-End Web (PHP) & Aplicação C# |
| **Gabriel de Souza Custódio Pereira** | `424205004` | Desenvolvimento Back-End Web (PHP) & Aplicação C# |

* **Orientação:** Prof(a). Marcio Klein — Disciplina de Projeto de Extensão em Programação (UNINOVE, 2026).

---

## 📄 Licença

Este projeto foi desenvolvido exclusivamente para fins acadêmicos e educacionais no âmbito do Projeto de Extensão da Universidade Nove de Julho (UNINOVE).