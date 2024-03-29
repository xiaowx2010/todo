<?php 
// *您可以通过该文件集中管理系统中的界面文字，让WSS更加适合您的企业。修改方法：找到相应的界面文字，修改引号“”之间的内容即可。

// *** 数据字典开始(系统正式运行后禁止修改数据字典)********************

  // *任务优先级
$multilingual_dd_priority_p5 = "5-极低";
$multilingual_dd_priority_p4 = "4-低";
$multilingual_dd_priority_p3 = "3-中";
$multilingual_dd_priority_p2 = "2-高";
$multilingual_dd_priority_p1 = "1-紧急";
  // *严重程度
$multilingual_dd_level_l5 = "5-极低";
$multilingual_dd_level_l4 = "4-低";
$multilingual_dd_level_l3 = "3-中";
$multilingual_dd_level_l2 = "2-高";
$multilingual_dd_level_l1 = "1-严重";
  // *是或否
$multilingual_dd_whether_yes = "是";
$multilingual_dd_whether_no = "否";
  // *只读用户帐号
$multilingual_dd_user_readonly = get_item( 'readonly' );
  // *项目选择排除条件(与后台管理项目状态管理对应)
$multilingual_dd_status_prjfinish = "结束";  
  // *任务搜索排除条件(与后台管理任务状态管理对应)
$multilingual_dd_status_stfinish = "完成";  
  // *任务待审核条件
$multilingual_dd_status_exam = "100%";  
  // *控制帐户的计划用时将不会纳入汇总
$multilingual_dd_status_ca = "null";  
  // *项目类型(该字段未开始使用，禁止修改)
$multilingual_dd_project_type = "选择类型";  
$multilingual_dd_project_inside = "内部项目";  
$multilingual_dd_project_outside = "外部项目";  
// *** 数据字典结束***********************************************

// *** 全局共用文字
  // *公告状态
$multilingual_dd_announcement_settop = "置顶";
$multilingual_dd_announcement_online = "上线";
$multilingual_dd_announcement_offline = "下线";

  // *用户角色
$multilingual_dd_role_admin = "管理员";
$multilingual_dd_role_pm = "项目经理";
$multilingual_dd_role_general = "普通用户";
$multilingual_dd_role_guest = "访客";
$multilingual_dd_role_readonly = "只读";
$multilingual_dd_role_disabled = "禁用";

$multilingual_global_all = "不限";
$multilingual_global_select = "请选择";
$multilingual_global_searchbtn = "搜索";
$multilingual_global_filterbtn = "过滤";
$multilingual_global_excel = "导出Excel";
$multilingual_global_excelfile = "WSS_File_";
$multilingual_global_resetbtn = "重置";
$multilingual_global_to = "到";
$multilingual_global_total = "总计";
$multilingual_global_first = "第一页";
$multilingual_global_previous = "上一页";
$multilingual_global_next = "下一页";
$multilingual_global_last = "最后一页";
$multilingual_global_mon = "星期一";
$multilingual_global_tues = "星期二";
$multilingual_global_wed = "星期三";
$multilingual_global_thur = "星期四";
$multilingual_global_fir = "星期五";
$multilingual_global_sat = "星期六";
$multilingual_global_sun = "星期日";
$multilingual_global_year = "年";
$multilingual_global_month = "月";
$multilingual_global_date = "日期";
$multilingual_global_hour = "小时";
$multilingual_global_lastupdate = "最后更新";
$multilingual_global_action = "操作";
$multilingual_global_action_create = "创建";
$multilingual_global_action_edit = "编辑";
$multilingual_global_action_del = "删除";
$multilingual_global_action_save = "保存";
$multilingual_global_action_ok = "确定";
$multilingual_global_action_publish = "发布";
$multilingual_global_action_cancel = "取消";
$multilingual_global_action_back = "返回";
$multilingual_global_action_close = "关闭";
$multilingual_global_action_delconfirm = "删除操作不可撤销,是否继续?";
$multilingual_global_action_delconfirm2 = "该任务已产生日志,强制删除任务将导致该任务的日志永久性的不可删除及查询。是否强制删除该任务?";
$multilingual_global_action_delconfirm3 = "该项目已产生任务,强制删除项目将导致该项目的任务永久性的不可删除及查询。是否强制删除该项目?";
$multilingual_global_action_delconfirm4 = "该用户已产生任务或正在负责某个项目,强制删除用户将导致该用户负责的任务和项目永久性的不可删除及查询(工时依然纳入统计)。建议禁用该用户,而非强制删除。依然强制删除该用户?";
$multilingual_global_action_delconfirm5 = "您即将删除一个文件夹，请确认该文件夹内没有文档或其他文件夹，否则删除操作将导致该文件夹内的文档或文件夹不可查询及删除，是否继续？";
$multilingual_global_action_delconfirm6 = "强制删除将导致所有使用了该项的任务/项目不可查询及删除，建议将其修改为需要的名称，而不是删除。是否强制删除？";
$multilingual_global_required = "必填项";
$multilingual_global_upload = "上传附件";
$multilingual_global_log = "日志";
$multilingual_global_version = "版本";
$multilingual_global_home = "首页";
$multilingual_global_break = "分解";
$multilingual_global_wait = "提交中，请稍候...";
$multilingual_global_success = "保存成功";

$multilingual_global_action_saveandgo = "保存并退出编辑";

// *** 提示信息
$multilingual_error_duplicate = "用户已存在";
$multilingual_error_duplicatetext = "<strong>错误:</strong> 用户名已存在，";
$multilingual_error_login = "请重新登录";
$multilingual_error_logintext = "<strong>错误:</strong> 请核对用户名或密码，";
$multilingual_error_permissions = "<strong>权限错误:</strong> 您不具备执行该操作的权限，请核对您的权限。";
$multilingual_error_permissions1 = "权限错误";
$multilingual_error_oursite = "该功能还未正式开放,请  <a href='http://www.wssys.net/zh-cn/feature.php' target='_blank'>登录我们的网站</a>  随时了解最新消息.";
$multilingual_error_date = "请输入正确的日期格式MMMM-YY-DD";

// *** 面包屑
$multilingual_breadcrumb_home = " <a href='index.php'>首页</a>";
$multilingual_breadcrumb_tasklist = " <a href='index.php'>任务</a>";
$multilingual_breadcrumb_projectlist = " <a href='project.php'>项目</a>";
$multilingual_breadcrumb_filelist = " 文档";
$multilingual_breadcrumb_userlist = " <a href='default_user.php'>用户</a>";
$multilingual_breadcrumb_anclist = " <a href='default_announcement.php'>公告</a>";

// *** 登录页面(user_login.php)
$multilingual_userlogin_title = "请先登录";
$multilingual_userlogin_username = "用户名";
$multilingual_userlogin_password = "密码";
$multilingual_userlogin_login = "登录";

// *** 前台主导航(head.php)
// *** 后台主导航(admin_head.php)
$multilingual_head_myhome = "我的首页";
$multilingual_head_task = "任务";
$multilingual_head_log = "日志";
$multilingual_head_project = "项目";
$multilingual_head_file = "文档";
$multilingual_head_user = "用户";
$multilingual_head_announcement = "公告";
$multilingual_head_hello = "您好";
$multilingual_head_edituserinfo = "修改个人信息";
$multilingual_head_backend = "设置";
$multilingual_head_frontend = "返回前台";
$multilingual_head_help = "<a href='http://www.wssys.net/zh-CN/file.php' target='_blank'>帮助</a>";
$multilingual_head_logout = "退出";
$multilingual_head_more = "更多";

$multilingual_head_tasklist = "任务列表";
$multilingual_head_loglist = "日志列表";

// *** 页面底部(foot.php)
$multilingual_foot_about = "关于WSS";
$multilingual_foot_abouttitle = "当前版本及作者";
$multilingual_foot_officialsite = "官方网站";
$multilingual_foot_officialsitetitle = "关于WSS最新的资讯";
$multilingual_foot_feedback = "<a href='http://www.wssys.net/zh-cn/feedback.php' target='_blank' title='我们将在第一时间对您提出的问题给予答复'>问题反馈</a>";
$multilingual_foot_feedbacktitle = "我们将在第一时间对您提出的问题给予答复";
$multilingual_foot_contact = "联系我们";

// *** 首页(index.php)
// *** 新建任务页(default_task_add.php)
// *** 编辑任务页(default_task_plan.php)
// *** 状态列表页(status_list.php)
// *** 编辑状态页(status_edit.php)
// *** 任务类型列表页(task_type_list.php)
// *** 编辑任务类型页(task_type_edit.php)
$multilingual_default_title = "Beta";
$multilingual_default_searchtask = "任务搜索";
$multilingual_default_taskto = "任务指派给谁:";
$multilingual_default_taskfrom = "任务来自谁:";
$multilingual_default_taskcreate = "任务由谁创建:";
$multilingual_default_taskproject = "所属项目:";
$multilingual_default_taskpriority = "任务优先级:";
$multilingual_default_tasktemp = "临时:";
$multilingual_default_tasklevel = "严重程度";
$multilingual_default_taskstatus = "任务状态:";
$multilingual_default_taskstatusf = "仅显示未完成任务";
$multilingual_default_tasktype = "任务类型:";
$multilingual_default_taskdate = "任务日期(年/月):";
$multilingual_default_taskyear = "任务年份:";
$multilingual_default_taskmonth = "任务月份:";
$multilingual_default_tasktitle = "任务标题:";
$multilingual_default_taskid = "任务ID:";
$multilingual_default_tasktag = "Tag";
$multilingual_default_searchtip = "默认显示当前用户、当年、当月的任务";
$multilingual_default_sorrytipup = "<strong>很抱歉!</strong> 没有符合条件的任务.";
$multilingual_default_sorrytipdown = "或尝试访问以下链接:";
$multilingual_default_newtask = "新建任务";
$multilingual_default_tome = "指派给我";
$multilingual_default_fromme = "来自我的任务";
$multilingual_default_createme = "我创建的任务";
$multilingual_default_shortcut = "快捷方式:";
$multilingual_default_task_id = "ID";
$multilingual_default_task_title = "任务名称";
$multilingual_default_task_description = "任务描述";
$multilingual_default_task_to = "指派给";
$multilingual_default_task_totalhour = "工时汇总";
$multilingual_default_task_manhour = "工时";
$multilingual_default_task_status = "状态";
$multilingual_default_task_planstart = "计划开始";
$multilingual_default_task_planend = "计划完成";
$multilingual_default_task_planhour = "计划用时(PV)";
$multilingual_default_task_type = "任务类型";
$multilingual_default_task_project = "所属项目";
$multilingual_default_task_from = "来自";
$multilingual_default_task_createby = "由谁创建";
$multilingual_default_task_editby = "由谁修改";
$multilingual_default_task_priority = "优先级";
$multilingual_default_task_temp = "临时";
$multilingual_default_task_week1 = "第一周";
$multilingual_default_task_week2 = "第二周";
$multilingual_default_task_week3 = "第三周";
$multilingual_default_task_week4 = "第四周";
$multilingual_default_task_week5 = "第五周";
$multilingual_default_task_week6 = "第六周";
$multilingual_default_task_preweek = "上一周";
$multilingual_default_task_nextweek = "下一周";
$multilingual_default_task_section1 = "任务分配";
$multilingual_default_task_section2 = "任务详情";
$multilingual_default_task_section3 = "任务基本信息";
$multilingual_default_task_section4 = "任务计划";
$multilingual_default_task_section5 = "工作日志";
$multilingual_default_task_others = "其他信息";
$multilingual_default_task_parent = "上级任务";
$multilingual_default_task_subtask = "子任务";
$multilingual_default_task_ca = "控制帐户";
$multilingual_default_task_catips = "<b>控制帐户(Control Account)</b>是指将当前任务作为一种管理控制点，控制帐户的工时数则为该任务的所有子任务及子任务下的所有节点的任务工时数汇总。";
$multilingual_default_task_catips2 = "【控制帐户(Control Account)】是指将当前任务作为一种管理控制点，可以简单的理解为:该任务还将分解出多个子任务(详情参考PMBOK-控制帐户)。";
$multilingual_default_addcom = "添加评论";
$multilingual_default_editcom = "编辑评论";
$multilingual_default_comment = "评论";
$multilingual_default_by = "于";
$multilingual_default_at = "添加的评论";
$multilingual_default_required1 = "请指定一个人";
$multilingual_default_required2 = "请选择一个项目";
$multilingual_default_required3 = "请选择任务类型";
$multilingual_default_required4 = "必填内容，不能为空";
$multilingual_default_required5 = "只能填写大于0的数字";
$multilingual_default_tasktips = "<strong>注意!</strong> 任务以月为单位，跨月需创建新任务。";
$multilingual_default_order = "排序";
$multilingual_tasklist_title = "任务管理";
$multilingual_taskadd_title = "新建任务";
$multilingual_taskedit_title = "编辑任务";
$multilingual_tasklog_title = "任务详情";
$multilingual_tasklog_edit = "编辑任务基本信息";
$multilingual_tasklog_cost = "已用工时(AC)";
$multilingual_tasklog_ev = "挣值(EV)";
$multilingual_tasklog_over = "超时";
$multilingual_tasklog_live = "剩余";
$multilingual_tasklog_overday = "该任务已到期";
$multilingual_tasklog_liveday = "距完工日期还有";
$multilingual_tasklog_day = "天(含节假日)";
$multilingual_tasklog_changeuser = "修改任务执行人";
$multilingual_tasklog_changeto = "任务执行人修改为";
$multilingual_tasklog_status = "请选择任务状态";
$multilingual_tasklog_delmsg1 = "是否删除该任务";
$multilingual_tasklog_delmsg2 = "日的工作日志及其相关评论？";
$multilingual_taskstatus_title = "日志/任务状态管理";
$multilingual_taskstatus_new = "新建状态";
$multilingual_taskstatus_name = "状态名称";
$multilingual_taskstatus_style = "状态显示样式";
$multilingual_taskstatus_edit = "编辑状态";
$multilingual_tasktype_title = "任务类型管理";
$multilingual_tasktype_new = "新建任务类型";
$multilingual_tasktype_edit = "编辑任务类型";  
$multilingual_tasktype_name = "任务类型名称";
$multilingual_tasktype_lock = "该任务已产生日志或没有指派给你，执行人不可编辑";
$multilingual_tasktype_lock2 = "任务已产生日志，该项不可编辑";
$multilingual_tasktype_condition = "当前查询条件:";
$multilingual_tasktype_moresearch = "高级搜索";
$multilingual_tasktype_generalsearch = "关闭高级搜索";
$multilingual_outofdate_title = "以下任务已到期，请优先处理或重新制定合理的时间";
$multilingual_outofdate_outofdate = "已过期";
$multilingual_outofdate_date = "天";
$multilingual_outofdate_totle = "个过期任务";

$multilingual_calendar_cost = "用时";
$multilingual_calendar_addlog = "单击填写日志";
$multilingual_calendar_editlog = "单击修改该日志";
$multilingual_calendar_view = "单击查看该日志";
$multilingual_calendar_donoedit = "不限年或月搜索时，无法在列表页进行日志操作。";
$multilingual_calendar_others = "该任务没有指派给您，无法进行日志操作";
$multilingual_calendar_premonth = "上一月";
$multilingual_calendar_nextmonth = "下一月";

$multilingual_taskf_year = "不限年份";
$multilingual_taskf_month = "不限月份";
$multilingual_taskf_status = "全部状态";
$multilingual_taskf_type = "全部类型";
$multilingual_taskf_priority = "全部优先级";
$multilingual_taskf_level = "全部严重程度";
$multilingual_taskf_project = "全部项目";
$multilingual_taskf_touser = "不限执行人";
$multilingual_taskf_fromuser = "不限来自谁";
$multilingual_taskf_createuser = "不限创建人";
$multilingual_tasks_title = "按任务名称搜索";
$multilingual_tasks_tid = "按任务ID搜索";
$multilingual_tasks_tag = "按任务TAG搜索";

$multilingual_subtask_root = "一级任务";
$multilingual_subtask_cost = "子任务工时汇总";
$multilingual_subtask_plan = "子任务计划用时汇总";

$multilingual_taskadd_selprj = "请选择项目或任务";
$multilingual_taskadd_back = "返回上一级";
$multilingual_taskadd_nosub = "该任务没有子任务";
$multilingual_default_alltask = "所有任务";

// *** 项目列表页(default_project.php)
// *** 项目详情页(project_view.php) 
// *** 新建项目页(project_add.php) 
// *** 编辑项目页(project_edit.php) 
// *** 项目状态列表页(project_status.php)
$multilingual_project_id = "ID";
$multilingual_project_title = "项目名称";
$multilingual_project_code = "项目代码";
$multilingual_project_start = "开始时间";
$multilingual_project_end = "结束时间";
$multilingual_project_touser = "负责人";
$multilingual_project_status = "项目状态";
$multilingual_project_ac = "实际成本(AC)";
$multilingual_project_ev = "挣值(EV)";
$multilingual_project_cv = "成本偏差(CV)";
$multilingual_project_cpi = "成本绩效指数(CPI)";
$multilingual_project_tcpi = "完工尚需绩效指数(TCPI)";
$multilingual_project_bac = "完工预算(BAC)";
$multilingual_project_eac = "完工估算(EAC)";
$multilingual_project_description = "项目概述";
$multilingual_project_partya = "甲方单位";
$multilingual_project_partyauser = "甲方联系人";
$multilingual_project_partyacon = "甲方联系方式";
$multilingual_project_remark = "评论";
$multilingual_project_none = "没有符合条件的项目";
$multilingual_project_none2 = "还没创建项目";
$multilingual_project_tips = "状态为已结束的项目在前台页面中不可选";
$multilingual_project_tips2 = "项目负责人对该项目拥有删除权限以外的所有权限";
$multilingual_project_view_title = "项目详情";
$multilingual_project_view_section1 = "项目基本信息";
$multilingual_project_view_section2 = "甲方信息";
$multilingual_project_view_section3 = "执行信息";
$multilingual_project_view_section4 = "相关任务";
$multilingual_project_view_wbs = "工作分解结构(WBS)";
$multilingual_project_view_log = "项目日志";
$multilingual_projectlist_title = "项目管理";
$multilingual_projectlist_new = "新建项目";
$multilingual_projectlist_search = "按项目名称搜索";
$multilingual_projectlist_edit = "编辑项目";
$multilingual_projectsub_title = "子项目管理 / 工作分解结构(WBS)管理";
$multilingual_projectsub_text = "在WSS 扩展功能中，项目管理功能得到了增强，您可以为一个项目创建多个子项目，从而解决项目过多不易管理的问题。<br />
您还可以将一个项目分解为多个任务包，从而实现项目的“工作分解结构(WBS)”，更加专业化的管理项目。";
$multilingual_projectstatus_title = "项目状态管理";
$multilingual_projectstatus_new = "新建项目状态";
$multilingual_projectstatus_name = "项目状态名称";
$multilingual_projectstatus_style = "项目状态显示样式";
$multilingual_projectstatus_edit = "编辑项目状态";
$multilingual_projectstatus_titlerequired = "2-32个字符";
$multilingual_project_taskoverlay = "任务分布情况";
$multilingual_project_hour = "小时";
$multilingual_project_newtask = "下发任务";

$multilingual_projectmem_title = "项目成员管理";
$multilingual_projectmem_text = "在WSS 扩展功能中，您可以为一个项目组添加固定的成员，所有项目相关的内容，如：任务、文档、日志、项目详情等，只对该项目组的成员开放。";

// *** 文档管理
$multilingual_project_file = "项目文档";
$multilingual_project_file_management = "文档";
$multilingual_project_file_search = "文档搜索";
$multilingual_project_file_searchr = "文档搜索结果";
$multilingual_project_file_word = "导出Word";
$multilingual_project_file_download = "下载附件";
$multilingual_project_file_update = "更新于";
$multilingual_project_file_addfile = "新建文档";
$multilingual_project_file_addfolder = "新建文件夹";
$multilingual_project_file_editfile = "编辑文档";
$multilingual_project_file_editfolder = "编辑文件夹";
$multilingual_project_file_description = "描述";
$multilingual_project_file_list = "文档列表";
$multilingual_project_file_foldertitle = "文件夹名称";
$multilingual_project_file_filetitle = "文件名";
$multilingual_project_file_filetext = "文档正文";
$multilingual_project_file_nofile = "没有符合条件的文档";

$multilingual_project_file_myfile = "我创建的文档";
$multilingual_project_file_myeditfile = "我编辑的文档";
$multilingual_project_file_allfile = "所有文档";

$multilingual_project_myprj = "我负责的项目";
$multilingual_project_jprj = "我参与的项目";
$multilingual_project_allprj = "所有项目";

// *** 用户列表页(default_user.php)
// *** 用户详情页(user_view.php)
// *** 新建用户页(user_add.php)
// *** 编辑用户页(user_edit.php)
$multilingual_user_title = "姓名";
$multilingual_user_name = "显示名称";
$multilingual_user_new = "新建用户";
$multilingual_user_selectrole = "选择角色";
$multilingual_user_account = "帐号";
$multilingual_user_password = "密码";
$multilingual_user_password2 = "确认密码";
$multilingual_user_newpassword = "新密码";
$multilingual_user_newpassword2 = "确认新密码";
$multilingual_user_role = "角色";
$multilingual_user_dept = "部门";
$multilingual_user_remark = "备注";
$multilingual_user_contact = "电话";
$multilingual_user_email = "电子邮件";
$multilingual_user_updatetask = "更新了任务";
$multilingual_user_updatetask2 = "的执行人";
$multilingual_user_newtask1 = "有新的任务";
$multilingual_user_newtask2 = "指派给您";
$multilingual_user_addcomment = "评论了您的任务：";
$multilingual_user_list_title = "用户管理";
$multilingual_user_list_showdis = "显示禁用用户";
$multilingual_user_list_search = "按用户名称搜索";
$multilingual_user_view_title = "用户详情";
$multilingual_user_view_userinfo = "用户基本信息";
$multilingual_user_view_project = "负责项目";
$multilingual_user_view_task = "近期任务";
$multilingual_user_view_date = "日期(年/月/日):";
$multilingual_user_view_nolog = "没有符合条件的工作日志,请修改日期后重试.";
$multilingual_user_view_user = "用户";
$multilingual_user_view_by = "于";
$multilingual_user_view_do = "执行了";
$multilingual_user_view_taskname = "任务";
$multilingual_user_view_project2 = "所属项目";
$multilingual_user_view_cost = "耗时";
$multilingual_user_view_hour = "小时";
$multilingual_user_view_status = "状态为";
$multilingual_user_edit_title = "编辑用户";
$multilingual_user_edit_info = "用户基本信息";
$multilingual_user_edit_password = "修改密码";
$multilingual_user_tip_account = "用户创建后帐号不可编辑";
$multilingual_user_tip_contact = "手机或分机等";
$multilingual_user_tip_remark = "职务、部门或详细的联系方式等，可记录在备注中";
$multilingual_user_tip_password = "密码采用多重加密，管理员密码如遗失将无法找回";
$multilingual_user_tip_password2 = "再次输入密码";
$multilingual_user_tip_match = "两次输入不一致";
$multilingual_user_tip_name = "建议用户名前增加拼音首字母(如:h韩英),以便查询";
$multilingual_user_tip_dis = "角色为禁用的用户将被禁止登录系统  <a href='http://www.wssys.net/zh-CN/help/index.html?page=_27.htm' target='_blank'>角色说明</a>";
$multilingual_user_tip_mail = "用于邮件提醒";
$multilingual_user_sorrytip = "没有符合条件的用户";
$multilingual_user_namequired = "2-12个字符";
$multilingual_user_alpha = "用户帐号只允许使用字母、数字或下划线";
$multilingual_dept_title = "部门管理";
$multilingual_dept_text = "对于拥有大量员工的企业，您可能会需要创建多个部门，以便将用户按部门分组进行管理。";

$multilingual_user_namequired8 = "2-8个字符";
$multilingual_user_shome = "的首页";
$multilingual_user_mytask = "指派给我的任务";
$multilingual_user_mylog = "我的日志";
$multilingual_user_myproject = "我的项目";
$multilingual_user_mydoc = "我的文档";

// *** 公告列表页(default_announcement.php)
// *** 公告详情页(announcement_view.php)
// *** 新建公告页(announcement_add.php)
// *** 编辑公告页(announcement_edit.php)
$multilingual_announcement_id = "ID";
$multilingual_announcement_title = "公告标题";
$multilingual_announcement_text = "公告正文";
$multilingual_announcement_publisher = "发布人";
$multilingual_announcement_status = "发布状态";
$multilingual_announcement_none = "目前没有公告";
$multilingual_announcement_tip = "状态为下线的公告对普通用户不可见.";
$multilingual_announcement_view_title = "公告详情";
$multilingual_announcement_view_time = "发布时间";
$multilingual_announcement_list_title = "公告管理";
$multilingual_announcement_list_showoff = "显示下线公告";
$multilingual_announcement_new_title = "发布公告";
$multilingual_announcement_edit_title = "编辑公告";
$multilingual_announcement_titlerequired = "2-30个字符";

// *** 版本验证页(update.php)
$multilingual_version_title = "版本验证";
$multilingual_version_yourversion = "您当前的版本是:";
$multilingual_version_update = "WSS有新版本发布, 您可以  <a href='http://www.wssys.net/zh-cn/newversion.php' target='_blank'><b>免费下载最新版</b></a>.";
$multilingual_version_nonet = "网络连接不可用, 无法验证您的版本。";
$multilingual_version_unew = "已经是最新版。";
$multilingual_version_upgrade = "WSS有新版本发布, 您可以";
$multilingual_version_upgrade1 = "马上升级";
$multilingual_version_upgrade2 = "<a href='http://www.wssys.net/zh-cn/newversion.php' target='_blank'>新版本功能介绍</a>";
$multilingual_version_done = "文件下载完成，<a href='upgrade1.php'><b>安装</b></a>";
$multilingual_version_error = "数据错误，<a href='index.php'><b>返回首页</b></a>";
$multilingual_version_error2 = "已经是最新版本无需升级，<a href='index.php'><b>返回首页</b></a>";
$multilingual_version_error3 = "接口请求错误，<a href='index.php'><b>返回首页</b></a>";

// *** 上传页(upload.php)
$multilingual_upload_title = "上传附件";
$multilingual_upload_button = "上传";
$multilingual_upload_tip = "允许上传的文件类型为: 图片、 SWF、 RAR、 Zip、 Html、 Doc、 PPT、 Excel、 TXT， 文件大小不超过2MB";
$multilingual_upload_error1 = "文件不存在！";
$multilingual_upload_error2 = "文件大小超出限制";
$multilingual_upload_error3 = "不支持该格式，请打包后上传。";
$multilingual_upload_error4 = "同名文件已经存在了！";
$multilingual_upload_error5 = "移动文件出错！";
$multilingual_upload_error6 = "不能上传此类型文件！";
$multilingual_upload_done = "已经成功上传，点击“确定”可将该附件添加至任务中。";
$multilingual_upload_img = "图片预览:";
$multilingual_upload_file = "文件名:";
$multilingual_upload_time = "上传时间:";
$multilingual_upload_attachment = "附件:";

// *** log相关
$multilingual_log_title = "操作记录";
$multilingual_log_addtask = "创建了任务";
$multilingual_log_edittask = "编辑了任务";
$multilingual_log_edittaskuser = "将任务转让给";
$multilingual_log_addlog1 = "填写了 ";
$multilingual_log_addlog3 = "修改了 ";
$multilingual_log_addlog2 = " 日志，任务状态为：";
$multilingual_log_costlog = "，耗时：";
$multilingual_log_adddoc = "创建了文档";
$multilingual_log_editdoc = "编辑了文档";
$multilingual_log_marklog1 = "评论了 ";
$multilingual_log_marklog2 = " 日志 ";
$multilingual_log_exam = "审核了任务";
$multilingual_log_exam2 = "审核结果为：";
$multilingual_log_exam1 = " ，审核意见：";

$multilingual_log_mylog = "我的日志";
$multilingual_log_newlog = "最近更新的日志";
$multilingual_log_comment = "评论";

// *** 设置
$multilingual_set_title = "设置";
$multilingual_set_baseset = "基本设置";
$multilingual_set_mailset = "邮件设置";
$multilingual_set_setup = "配置";

// *** 审核
$multilingual_exam_title = "审核";
$multilingual_exam_poptitle = "审核任务";
$multilingual_exam_select = "请选择审核结果";
$multilingual_exam_text = "审核意见";
$multilingual_exam_tip = "(任务由谁审核)";
$multilingual_exam_wait = "待我审核的任务";
$multilingual_exam_check = "作为审核结果";
$multilingual_exam_yes = "是";

$multilingual_exam_tip2 = "审核过的任务可以在【来自我的任务】中查询。";

// *** 权限
$multilingual_rank1 = "登录系统";
$multilingual_rank2 = "填写日志";
$multilingual_rank3 = "添加评论";
$multilingual_rank4 = "文档管理";
$multilingual_rank5 = "分派任务";
$multilingual_rank6 = "项目管理";
$multilingual_rank7 = "用户管理";
$multilingual_rank8 = "公告管理";
$multilingual_rank9 = "系统设置";
?>