<?php

class Media_Model extends Model {
	public function getMedias(){
		$medias = DB::select('
				SELECT id, message, time,category_id
				FROM posts
				WHERE official=1
				AND	category_id=1
				OR category_id=2
				OR category_id=3
				OR category_id=4
				OR category_id=10
				ORDER BY time DESC
			');
		return $medias;
	}
}
?>