import { extend } from 'flarum/extend';
import app from 'flarum/app';
import Modal from 'flarum/components/Modal';
import Button from 'flarum/components/Button';
import FieldSet from 'flarum/components/FieldSet';
import username from 'flarum/helpers/username';
import icon from 'flarum/helpers/icon';
import Stream from "flarum/utils/Stream";
import DiscussionList from "flarum/components/DiscussionList"


export default class fundModal extends Modal {
	oninit(vnode) {
    	super.oninit(vnode);
	}
	className() {
    	return 'Community Fund Modal--small';
  	}

  	title() {
    	return app.translator.trans('chen-nbdomain-login.forum.fund');
  	}
  	content() {
  		 return (
	      <div className="Modal-body">
	        <div className="Form Form--centered">
	          <div className="Form-group">
	            <input
	              type="email"
	              name="email"
	              className="FormControl"
	              placeholder={app.session.user.email()}
	              bidi={this.email}
	              disabled={this.loading}
	            />
	          </div>
	          <div className="Form-group">
	            <input
	              type="password"
	              name="password"
	              className="FormControl"
	              placeholder={app.translator.trans('core.forum.change_email.confirm_password_placeholder')}
	              bidi={this.password}
	              disabled={this.loading}
	            />
	          </div>
	          <div className="Form-group">
	            {Button.component(
	              {
	                className: 'Button Button--primary Button--block',
	                type: 'submit',
	                loading: this.loading,
	              },
	              app.translator.trans('core.forum.change_email.submit_button')
	            )}
	          </div>
	        </div>
	      </div>
    );
  	}
}