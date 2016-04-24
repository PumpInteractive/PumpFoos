function Player(id, slack_user_id, slack_user_name, slack_profile_pic_url)
{
	this.id = id;
	this.slack_user_id = slack_user_id;
	this.slack_user_name = slack_user_name;
	this.slack_profile_pic_url = slack_profile_pic_url;

	this.team = null;
	this.position = null;
	this.tray_id = null;
}