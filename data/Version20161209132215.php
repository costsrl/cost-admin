<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * A migration class. It either upgrades the databases schema (moves it to new state)
 * or downgrades it to the previous state.
 */
class Version20161209132215 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription() :string
    {
        $description = 'A migration which creates modules system tables.';
        return $description;
    }
    
    /**
     * Reverts the schema changes.
     * @param Schema $schema
     */
    /**
     * Reverts the schema changes.
     * @param Schema $schema
     */
    public function down(Schema $schema) :void
    {
        $schema->dropTable('menu');
        $schema->dropTable('permission');
        $schema->dropTable('resources');
        $schema->dropTable('role');
        $schema->dropTable('user');
        $schema->dropTable('[language]');
        $schema->dropTable('TranslationTable');
    }
    
    /**
     * Upgrades the schema to its newer state.
     * @param Schema $schema
     */
    
    public function up(Schema $schema) :void
    {
        $this->addSql('CREATE TABLE user (user_id INT IDENTITY NOT NULL, role_id INT, language_id INT, username NVARCHAR(100) NOT NULL, display_name NVARCHAR(100) NOT NULL, first_name NVARCHAR(40) NULL, last_name NVARCHAR(40) NULL, password NVARCHAR(100), email NVARCHAR(60) NOT NULL, state INT NOT NULL, 
question NVARCHAR(100) NULL, 
answer NVARCHAR(100) NULL, 
picture NVARCHAR(255) NULL, 
password_salt NVARCHAR(100), 
registration_date DATETIME2(6) NULL, 
lastlogin DATETIME2(6) NULL, 
registration_token NVARCHAR(100), 
email_confirmed INT NOT NULL, 
PRIMARY KEY (user_id));');
        $this->addSql('');
        
        $this->addSql('CREATE INDEX IDX_LANGUAGE ON user (language_id);');
        $this->addSql('CREATE INDEX role_id ON user (role_id);');
        $this->addSql('CREATE TABLE language (id INT IDENTITY NOT NULL, name NVARCHAR(15) NOT NULL, code INT NOT NULL, default_language INT NOT NULL, PRIMARY KEY (id));');
        
        $this->addSql('CREATE TABLE permissions (id INT IDENTITY NOT NULL, resource_id INT, role_id INT, name NVARCHAR(100), privilege NVARCHAR(255) NOT NULL, permission_allow INT NOT NULL, assert_class NVARCHAR(255) NULL, PRIMARY KEY (id));');
        $this->addSql('CREATE INDEX IDX_RESOURCE ON permissions (RESOURCE_ID);');
        $this->addSql('CREATE INDEX IDX_ROLE ON permissions (ROLE_ID);');
        
        $this->addSql('CREATE TABLE roles (id INT IDENTITY NOT NULL, parent_id  INT NULL, name NVARCHAR(15) NOT NULL, PRIMARY KEY (id));');
        //$this->addSql('CREATE INDEX IDX_PARENT_ID ON roles (parent_id);');
        
        $this->addSql('CREATE TABLE resources (id INT IDENTITY NOT NULL, name NVARCHAR(100) NOT NULL, type NVARCHAR(100) NOT NULL, 
PRIMARY KEY (id));');
        
        $this->addSql('CREATE TABLE TranslationTable (messageId INT IDENTITY NOT NULL, translationKey NVARCHAR(255) NOT NULL, textEn NVARCHAR(255) NOT NULL, textIt NVARCHAR(255) NOT NULL, textDe NVARCHAR(255) NOT NULL, PRIMARY KEY (messageId))');
        
        
        $this->addSql('CREATE TABLE menus (id INT IDENTITY NOT NULL, privilege INT, parent_id INT, name NVARCHAR(255) NOT NULL, label NVARCHAR(255) NOT NULL, route NVARCHAR(255) NOT NULL, 
controller NVARCHAR(255) NOT NULL, action NVARCHAR(255) NOT NULL, resource NVARCHAR(255), params NVARCHAR(255), query NVARCHAR(255), module NVARCHAR(255) NOT NULL, icon NVARCHAR(255) NOT NULL, 
class NVARCHAR(255), sort_order INT NOT NULL, PRIMARY KEY (id))');
        
        $this->addSql('CREATE INDEX IDX_PRIVILEGES ON menus (privilege)');
        
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_USER_ROLE_ID FOREIGN KEY (role_id) REFERENCES roles (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_USER_LANGUAGE_ID FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_PERM_RES FOREIGN KEY (resource_id) REFERENCES resources (id)');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_PERM_ROLE_ID FOREIGN KEY (role_id) REFERENCES roles (id)');
        $this->addSql('ALTER TABLE roles ADD CONSTRAINT FK_ROLE_PARENT_ID FOREIGN KEY (parent_id) REFERENCES roles (id)');
        $this->addSql('ALTER TABLE menus ADD CONSTRAINT FK_PERMISSION_PRIVILEGS FOREIGN KEY (privilege) REFERENCES permissions (id)');
        
    }
        
    
    
}

