--1. Create load cache
delete from ruakraq.jira_times where team = 'TEAM-1';
insert into ruakraq.jira_times
    select 
         i.issuekey,
        (select min(sdate)  from ru_l_jira.statuschangehistory where issuekey = i.issuekey and status in ('AF Backlog', 'Submitted', 'Open') and date_from_gdwh_ru = i.load_date) create_date,
        (select min(sdate)  from ru_l_jira.statuschangehistory where issuekey = i.issuekey and status in ('AF BA Analysis', 'Analyzing', 'In Progress') and date_from_gdwh_ru = i.load_date) start_date,
        (select max(sdate)  from ru_l_jira.statuschangehistory where issuekey = i.issuekey and status in ('AF UAT', 'UAT') and date_from_gdwh_ru = i.load_date) uat_date,
        (select max(sdate)  from ru_l_jira.statuschangehistory where issuekey = i.issuekey and status in ('Closed', 'AF Closed') and date_from_gdwh_ru = i.load_date) close_date,
         i.issue_status,
        'TEAM-1' team
    from (
        select c.issuekey, c.issue_status, c.labels, d.load_date from 
        (select i.*, e.labels from ru_l_jira.issue i 
            left join ru_l_jira.issueextended e on i.issuekey = e.issuekey and i.date_from_gdwh_ru = e.date_from_gdwh_ru) c,
        (select trunc(current_date-1) load_date from dual) d
        where c.projectid  in (select PROJECTID from ru_l_jira.projects where projectkey in ('TEAM-1') and date_from_gdwh_ru =  d.load_date) 
        and c.issue_status <> 'AF Closed Down' 
        and c.classofservice = 'System Family Task'
        --and c.issuekey = 'TEAM-1-25593'
        and c.issuekey not in ('TEAM-1-11', 'TEAM-1-5541','TEAM-1-8429', 'TEAM-1-10238', 'TEAM-1-31548')
        and c.labels not like '%NoMetriсs%'
        and c.issue_type in ('Business Request') 
        and c.date_from_gdwh_ru =  d.load_date 
        --and c.created >= to_date('2015-01-01', 'yyyy-mm-dd')
        --and rownum < 50
    ) i;

--2. Refine
delete from ruakraq.jira_times_refine where team = 'TEAM-1';
insert into ruakraq.jira_times_refine
    select * from ruakraq.jira_times t where 
        ((create_date is not null and  start_date is null and uat_date is null and close_date is null) 
        or (create_date is not null and  start_date is not null and uat_date is null and close_date is null) 
        or (create_date is not null and  start_date is not null and uat_date is not null and close_date is null) 
        or (create_date is not null and  start_date is not null and uat_date is not null and close_date is not null))
        and team = 'TEAM-1'
;


