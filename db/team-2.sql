--1. Create load cache
delete from ruakraq.jira_times where team = 'TEAM-2';
insert into ruakraq.jira_times
    select 
         i.issuekey,
        (select min(sdate)  from statuschangehistory where issuekey = i.issuekey and status in ('AF Backlog') and date_from_gdwh_ru = i.load_date) create_date,
        (select min(sdate)  from statuschangehistory where issuekey = i.issuekey and status in ('AF BA Analysis') and date_from_gdwh_ru = i.load_date) start_date,
        (select max(sdate)  from statuschangehistory where issuekey = i.issuekey and status in ('AF UAT', 'AF Test Done') and date_from_gdwh_ru = i.load_date) uat_date,
        (select max(sdate)  from statuschangehistory where issuekey = i.issuekey and status in ('AF Closed', 'Closed') and date_from_gdwh_ru = i.load_date) close_date,
         i.issue_status,
        'TEAM-2' team
    from (
        select c.issuekey, c.issue_status, d.load_date
        from issue c, (select trunc(current_date-1) load_date from dual) d
        where c.projectid  in (select PROJECTID from projects where projectkey in ('LORD', 'EP') and date_from_gdwh_ru =  d.load_date) 
        and c.issue_status <> 'AF Closed Down' 
        and c.created >= to_date('2016-06-01', 'yyyy-mm-dd')
        and c.issue_type in ('User Story') and date_from_gdwh_ru =  d.load_date 
        --and rownum < 50
    ) i;

--2. Refine
delete from ruakraq.jira_times_refine where team = 'TEAM-2';
insert into ruakraq.jira_times_refine
    select * from ruakraq.jira_times t where 
        ((create_date is not null and  start_date is null and uat_date is null and close_date is null) 
        or (create_date is not null and  start_date is not null and uat_date is null and close_date is null) 
        or (create_date is not null and  start_date is not null and uat_date is not null and close_date is null) 
        or (create_date is not null and  start_date is not null and uat_date is not null and close_date is not null))
        and team = 'TEAM-2' and (close_date >= to_date ('01.06.2016', 'dd.mm.yyyy') or close_date is null)
;
