<?php
class CouponExtension extends DataExtension {
	
	public function canView($member) {
		if(!$member) $member = Member::currentUser();
		return $member->inGroups(array('administrators', 'shop-admin'));
	}
	public function canEdit($member) {
		if(!$member) $member = Member::currentUser();
		return $member->inGroups(array('administrators', 'shop-admin'));
	}
	public function canDelete($member) {
		if(!$member) $member = Member::currentUser();
		return $member->inGroups(array('administrators', 'shop-admin'));
	}
	public function canCreate($member) {
		if(!$member) $member = Member::currentUser();
		return $member->inGroups(array('administrators', 'shop-admin'));
	}
}