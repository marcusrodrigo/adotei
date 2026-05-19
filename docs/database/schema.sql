CREATE TYPE "perfil_usuario" AS ENUM (
  'comum',
  'ong_protetor'
);

CREATE TYPE "status_solicitacao" AS ENUM (
  'pendente',
  'em_analise',
  'aprovado',
  'rejeitado',
  'concluido'
);

CREATE TYPE "especie_animal" AS ENUM (
  'cao',
  'gato',
  'outros'
);

CREATE TYPE "porte_animal" AS ENUM (
  'pequeno',
  'medio',
  'grande'
);

CREATE TABLE "usuarios" (
  "id" SERIAL PRIMARY KEY,
  "nome" varchar(255) NOT NULL,
  "email" varchar(255) UNIQUE NOT NULL,
  "senha" varchar(255) NOT NULL,
  "perfil" perfil_usuario NOT NULL DEFAULT 'comum',
  "homologado" boolean NOT NULL DEFAULT false,
  "cpf" varchar(14) UNIQUE,
  "telefone" varchar(20),
  "criado_em" timestamp DEFAULT (now())
);

CREATE TABLE "animais" (
  "id" SERIAL PRIMARY KEY,
  "usuario_id" integer NOT NULL,
  "nome" varchar(100) NOT NULL,
  "especie" especie_animal NOT NULL,
  "porte" porte_animal NOT NULL,
  "idade_estimada" varchar(50) NOT NULL,
  "localizacao_cidade" varchar(100) NOT NULL,
  "localizacao_estado" varchar(2) NOT NULL,
  "descricao" text NOT NULL,
  "foto_url" varchar(255) NOT NULL,
  "status_ativo" boolean NOT NULL DEFAULT true,
  "atualizado_em" timestamp DEFAULT (now()),
  "criado_em" timestamp DEFAULT (now())
);

CREATE TABLE "solicitacoes" (
  "id" SERIAL PRIMARY KEY,
  "animal_id" integer NOT NULL,
  "adotante_id" integer NOT NULL,
  "motivacao" text NOT NULL,
  "status" status_solicitacao NOT NULL DEFAULT 'pendente',
  "criado_em" timestamp DEFAULT (now())
);

CREATE UNIQUE INDEX ON "solicitacoes" ("animal_id", "adotante_id");

COMMENT ON COLUMN "usuarios"."senha" IS 'Armazenará hash seguro (RNF01)';
COMMENT ON COLUMN "usuarios"."homologado" IS 'Define se exibe selo "Não Verificado" (RF01)';
COMMENT ON COLUMN "usuarios"."cpf" IS 'Dado sensível (RN01)';
COMMENT ON COLUMN "usuarios"."telefone" IS 'Dado sensível (RN01)';
COMMENT ON COLUMN "animais"."usuario_id" IS 'Criador do anúncio (ONG ou Usuário Comum)';
COMMENT ON COLUMN "animais"."foto_url" IS 'Garante o critério de aceite do RF01 (pelo menos uma imagem)';
COMMENT ON COLUMN "animais"."status_ativo" IS 'RN02 (Inativo após 60 dias sem ação)';
COMMENT ON COLUMN "solicitacoes"."adotante_id" IS 'Vincula o usuário logado que tem interesse (RF02)';
COMMENT ON COLUMN "solicitacoes"."motivacao" IS 'Exigido no formulário do RF02';
COMMENT ON COLUMN "solicitacoes"."status" IS 'Estados lógicos do RF03';

ALTER TABLE "animais" ADD FOREIGN KEY ("usuario_id") REFERENCES "usuarios" ("id") ON DELETE CASCADE;
ALTER TABLE "solicitacoes" ADD FOREIGN KEY ("animal_id") REFERENCES "animais" ("id") ON DELETE CASCADE;
ALTER TABLE "solicitacoes" ADD FOREIGN KEY ("adotante_id") REFERENCES "usuarios" ("id") ON DELETE CASCADE;