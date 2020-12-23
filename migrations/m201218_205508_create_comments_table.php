<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comments}}`.
 */
class m201218_205508_create_comments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comments}}', [
            'id' => $this->primaryKey(),
            'text'=>$this->string()->notNull(),
            'user_id'=>$this->integer()->notNull(),
            'post_id'=>$this->string()->notNull(),
            'created_at'=>$this->datetime(),
        ]);

        $this->createIndex(
            'idx-comments-user_id',
            'comments',
            'user_id'
        );

        $this->addForeignKey(
            'fk-comments-user_id',
            'comments',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-comments-post_id',
            'comments',
            'post_id'
        );

        $this->addForeignKey(
            'fk-comments-post_id',
            'comments',
            'post_id',
            'posts',
            'id',
            'CASCADE'
        );
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%comments}}');
    }
}
