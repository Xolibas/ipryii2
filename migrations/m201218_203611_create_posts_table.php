<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%posts}}`.
 */
class m201218_203611_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%posts}}', [
            'id' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'status' => $this->boolean()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at'=> $this->datetime()->notNull(),
            'updated_at'=> $this->datetime(),
            'PRIMARY KEY(id)',
        ]);

        $this->createIndex(
            'idx-posts-user_id',
            'posts',
            'user_id'
        );
    
        $this->addForeignKey(
            'fk-posts-user_id',
            'posts',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%posts}}');
    }
}
