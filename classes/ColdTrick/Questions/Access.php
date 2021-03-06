<?php

namespace ColdTrick\Questions;

class Access {
	
	/**
	 * After the question is updated in the databse make sure the answers have the same access_id
	 *
	 * @param string      $event  the name of the event
	 * @param string      $type   the type of the event
	 * @param \ElggObject $entity the affected object
	 *
	 * @return void
	 */
	public static function updateQuestion($event, $type, $entity) {
		
		if (!$entity instanceof \ElggQuestion) {
			return;
		}
		
		$org_attributes = $entity->getOriginalAttributes();
		if (elgg_extract('access_id', $org_attributes) === null) {
			// access wasn't updated
			return;
		}
		
		// ignore access for this part
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity) {
			
			$answers = $entity->getAnswers(['limit' => false]);
			if (empty($answers)) {
				return;
			}
			
			/* @var $answer \ElggAnswer */
			foreach ($answers as $answer) {
				// update the access_id with the questions access_id
				$answer->access_id = $entity->access_id;
				
				$answer->save();
			}
		});
	}
}
