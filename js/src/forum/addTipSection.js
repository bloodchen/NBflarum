import { extend } from 'flarum/extend';
import app from 'flarum/app';
import CommentPost from 'flarum/components/CommentPost';
import Button from 'flarum/components/Button';
import username from 'flarum/helpers/username';
import icon from 'flarum/helpers/icon';
import Stream from "flarum/utils/Stream";

import addTipModal from './addTipModal';

export default function() {
		
	extend(CommentPost.prototype, 'footerItems', function(items) {		
		const post = this.attrs.post;
		const user = post.user();
		var curruser = app.session.user;
		var vcurruser = 0;
		if (typeof curruser === 'undefined') {
			//const opayaddressx = curruser.opayaddress();
			//console.log(Stream(this.attrs.user.web3address()));
		} else {
			vcurruser = curruser.data.id;
		}
		console.log('user_id ' + vcurruser);
		//console.log(user);
		console.log(user['data']['attributes']['opayaddress']);
		const tiplist = "tiplist" + post.data.id; 
		const btnlist = "btnlist" + post.data.id; 
		const tiplistlabel = app.translator.trans(`chen-nbdomain-login.forum.tip_list`);
		const no_tips = app.translator.trans(`chen-nbdomain-login.forum.no_tips`);
		items.add('nbdomaintipslist', (
			<div className="NBDomainTipsList">
				<div id={tiplist}></div>
				{Button.component(
				  {
					className: 'Button Button--link',
					style: 'padding-bottom: 14px !important;',
					id: btnlist,
					onclick: () => {
						console.log(tiplistlabel[0]);
						//if (document.getElementById(btnlist).innerText === tiplistlabel[0]) {
							console.log(document.getElementById(btnlist).innerText);
							var xhttp2 = new XMLHttpRequest();
							xhttp2.onreadystatechange = function() {
								if (this.readyState == 4 && this.status == 200) {
									const objx = JSON.parse(this.responseText);
									var datax = objx.data;
									var imn = 0;
									var listdata = document.getElementById(tiplist);
									if (datax.length > 0) {
										var lili = '<tr><td>Sender</td><td align="right">amount</td><td align="center">Date</td>';
										console.log(datax.length);
										for (var i = 0; i < datax.length; i++) { 
											console.log(i);
											if (datax[i].attributes.senderId === 0) {
												if ((i+1) === datax.length) {
													lili = lili + '<tr><td>Guest</td><td align="right">'+datax[i].attributes.tipsAmount+'</td><td>'+datax[i].attributes.date+'</td></tr>';
													listdata.innerHTML = '<table border="1">'+lili+'</table>';
												} else {
													lili = lili + '<tr><td>Guest</td><td align="right">'+datax[i].attributes.tipsAmount+'</td><td>'+datax[i].attributes.date+'</td></tr>';
												}
											} else {
												imn = i;
												app.store.find('users', datax[i].attributes.senderId).then(
													//user => dicoba(user)
													function(user) {
														if ((imn+1) === datax.length) {
															lili = lili + '<tr><td>'+user.data.attributes.displayName+'</td><td align="right">'+datax[imn].attributes.tipsAmount+'</td><td>'+datax[imn].attributes.date+'</td></tr>';
															listdata.innerHTML = '<table border="1">'+lili+'</table>';
														} else {
															console.log(user.data.attributes.displayName);
															console.log(datax[imn].attributes.tipsAmount);
															lili = lili + '<tr><td>'+user.data.attributes.displayName+'</td><td align="right">'+datax[imn].attributes.tipsAmount+'</td><td>'+datax[imn].attributes.date+'</td></tr>';
															listdata.innerHTML = '<table border="1">'+lili+'</table>';
														}
													}
												);
												
											}
											//console.log(username(data[i].attributes.senderId));
										}
										//listdata.innerHTML = '<table border="1">'+lili+'</table>';
										//document.getElementById(btnlist).innerHTML = '<span>Hide</span>';
									} else {
										listdata.innerHTML = no_tips[0];
									}
								}
							};
							xhttp2.open("GET", app.forum.attribute('baseUrl') + "/posts/" + post.data.id + "/nbdomaintipslist?include=user", true);
							xhttp2.send();
						//} else {
							document.getElementById(btnlist).innerHTML = '<span>'+tiplistlabel[0]+'</span>';
						//}							
					}
				  },
				  app.translator.trans(`chen-nbdomain-login.forum.tip_list`)
				
				)}
			</div>
		));
		
		const tip_text_id = "tip" + post.data.id; 
	    const tip_text_style = "width: 55px;";
		const tip_div = "div_tip" + post.data.id; 
		if (curruser !== user) {
		  items.add('nbdomaintips', (
			<div className="NBDomainTips" id={tip_div}>				
				{Button.component(
				  {
					className: 'Button Button--link',
					style: 'padding-bottom: 14px !important;',
					onclick: () => {
						const attrs = { user_id: user.data.id };
						attrs['div_tip_id'] = items.get('nbdomaintips');
						attrs['post'] = post;
						attrs['sender_id'] = vcurruser;
						attrs['tips_amount'] = parseInt(document.getElementById(tip_text_id).value);
						attrs['address'] = user['data']['attributes']['opayaddress'];
						app.modal.show(addTipModal, attrs);	  	
						console.log(items.get('nbdomaintips')['children'][2]['attrs']['value']);
						console.log(document.getElementById(tip_text_id).value);
						
					}
				  },
				  app.translator.trans(`chen-nbdomain-login.forum.tip_text`)
				
				)}&nbsp;<input type="number" id={tip_text_id} style={tip_text_style} min="2" value="2" />
			</div>
		  ));
		}
  });
}

function dicoba(datak) {
	console.log(datak);
}
