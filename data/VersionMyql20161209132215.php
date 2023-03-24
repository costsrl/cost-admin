<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use phpDocumentor\Reflection\Types\True_;

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

        // DLL MYSQL

        $this->addSql('CREATE TABLE IF NOT EXISTS `TranslationTable`  
                (`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
                translationKey varchar(255) NOT NULL, 
                textEn varchar(255) DEFAULT NULL, 
                TextIt varchar(255) DEFAULT NULL, 
                textDe varchar(255) DEFAULT NULL) 
                ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
                ;');


        $this->addSql('CREATE TABLE IF NOT EXISTS `permissions` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `resource_id` int(11) DEFAULT NULL,
              `role_id` int(11) DEFAULT NULL,
              `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
              `privilege` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `permission_allow` int(11) NOT NULL,
              `assert_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              ADD INDEX `permission_resource` (`resource_id`), 
              ADD KEY `permisssion_role` (`role_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');


        $this->addSql('CREATE TABLE IF NOT EXISTS `resources` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
              `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');


        $this->addSql('CREATE TABLE IF NOT EXISTS `menus` (
              `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `parent_id` int(11) DEFAULT NULL,
              `name` varchar(255) NOT NULL,
              `label` varchar(255) NOT NULL,
              `route` varchar(255) NOT NULL,
              `controller` varchar(11) NOT NULL,
              `action` varchar(255) NOT NULL,
              `resource` varchar(255) DEFAULT NULL,
              `privilege` int(11) DEFAULT NULL,
              `params` varchar(255) DEFAULT NULL,
              `query` varchar(255) DEFAULT NULL,
              `module` varchar(255) NOT NULL,
              `icon` varchar(255) DEFAULT NULL,
              `sort_order` int(11) NOT NULL,
              INDEX `menu_privileges` (`privilege`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1;');


        $this->addSql('CREATE TABLE IF NOT EXISTS `roles` (
          `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
          `parent_id` int(11) DEFAULT NULL,
           ADD UNIQUE INDEX `role_parent` (`parent_id`), 
           ADD KEY `parent_id` (`parent_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');



        $this->addSql('CREATE TABLE IF NOT EXISTS `user` (
          `user_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `role_id` int(11) DEFAULT NULL,
          `language_id` int(11) DEFAULT NULL,
          `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `display_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `first_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
          `last_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
          `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `password_salt` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
          `email_confirmed` tinyint(1) NOT NULL,
          `state` int(11) NOT NULL,
          `question` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          `answer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          `registration_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          `registration_date` datetime DEFAULT NULL,
          `lastlogin` datetime DEFAULT NULL,
           ADD INDEX `role_id` (`role_id`), 
           ADD KEY `user_language` (`language_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

















    }



}

