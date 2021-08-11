<?php

namespace Chen\NbdomainLogin\Listener;

//use Flarum\Post\Event\Deleted;
use Flarum\Post\Event\Saving;
use Chen\NbdomainLogin\PostNbdomaintips;

class SaveTipsToDatabase
{
    /**
     * @param Saving $event
     */
    public static function whenPostIsSaving(Saving $event)
    {
        $post = $event->post;
        $data = $event->data;

        if ($post->exists && isset($data['attributes']['trx']) && isset($data['attributes']['tips_amount'])) {
            $actor = $event->actor;
            $tips_amount = $data['attributes']['tips_amount'];
            $trx = $data['attributes']['trx'];
            $sender_id = $data['attributes']['sender_id'];

            //$actor->assertCan('like', $post);

            //$currentlyLiked = $post->nbdomaintips()->where('user_id', $actor->id)->exists();

            //$post->nbdomaintips()->attach($actor->id);
			$postNbdomaintips = new PostNbdomaintips();

			$postNbdomaintips->post_id = $post->id;
			$postNbdomaintips->user_id = $actor->id;
			$postNbdomaintips->sender_id = $sender_id;
			$postNbdomaintips->tips_amount = $tips_amount;
			$postNbdomaintips->save();
        }
    }

    /**
     * @param Deleted $event
     */
    public static function whenPostIsDeleted(Deleted $event)
    {
        $event->post->nbdomaintips()->detach();
    }
}
