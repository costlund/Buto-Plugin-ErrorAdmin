SELECT 
created_at,
HTTP_HOST,
REQUEST_URI,
error_message,
error_file,
error_line,
error_type,
HTTP_REFERER,
session,
phpversion
FROM buto_se_db_1.errorlog_log 
where HTTP_HOST <> 'localhost'
order by created_at desc
limit 200
;

#zzzdelete from errorlog_log;
#select distinct HTTP_HOST from errorlog_log;

#select count(*) from errorlog_log;




#select count(id) as sum from errorlog_log where left(created_at,10)=left(date_add(now(), interval -2 day),10) and HTTP_HOST<>'localhost'
;