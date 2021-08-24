import { extend } from 'flarum/extend';
import app from 'flarum/app';
import ChangeEmailModal from 'flarum/components/ChangeEmailModal';
import SettingsPage from 'flarum/components/SettingsPage'
import Button from 'flarum/components/Button';
import FieldSet from 'flarum/components/FieldSet';
import username from 'flarum/helpers/username';
import icon from 'flarum/helpers/icon';
import Stream from "flarum/utils/Stream";


export default function() {
		
	extend(ChangeEmailModal.prototype, 'content',  function(component) {	
		const pass = component.children[0].children[1].children[0]
		console.log(pass)
		this.password = Stream('12345678poi')
		component.children[0].children[1].children.splice(0)
		return 
	})
	extend(SettingsPage.prototype,'accountItems',function(items){
		items.remove('changePassword')
	})
}
