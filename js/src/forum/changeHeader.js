import { extend } from 'flarum/extend';
import app from 'flarum/app';
import HeaderSecondary from 'flarum/components/HeaderSecondary';
import SettingsPage from 'flarum/components/SettingsPage'
import Button from 'flarum/components/Button';
import FieldSet from 'flarum/components/FieldSet';
import username from 'flarum/helpers/username';
import icon from 'flarum/helpers/icon';
import Stream from "flarum/utils/Stream";
import DiscussionList from "flarum/components/DiscussionList";
import fundModal from "./fundModal"

export default function() {

	extend(HeaderSecondary.prototype, 'items',  function(items) {	
		items.add(
          'fund',
          Button.component(
            {
            	icon:'fas fa-hand-holding-usd',
              	className: 'Button Button--icon Button--flat',
              	onclick: () => {
              		console.log("click fund")
              		app.modal.show(fundModal)
              	},
            },
            app.translator.trans('chen-nbdomain-login.forum.fund')
          ),
          10
        );
		return 
	})

}