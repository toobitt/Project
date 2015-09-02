create procedure clear_push_data(in in_member_id int(10))
begin
	declare get_status_id int(10);
	select status_id into get_status_id from liv_status_push WHERE member_id=in_member_id  ORDER BY status_id DESC LIMIT 500 , 1;
	if get_status_id THEN
		begin
			delete from liv_status_push where member_id=in_member_id AND status_id < get_status_id;
		end;
	END IF;
end


CREATE TRIGGER `t_i_liv_member` AFTER INSERT ON `liv_member`
 FOR EACH ROW begin
	insert into sns_ucenter.liv_new_member (member_id,type) values (NEW.id,0);
end
//