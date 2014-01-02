<script language="c#" runat="server">
private void Click_Save(object sender, System.EventArgs e)
{   
    BeforeSave();
    
    //��֤�����ֶ�����
    CheckMustAndFieldType(mid);
    if (GetSESSION("alert").Length > 0)//��֤δͨ��
    {
        Response.Redirect("execommand.aspx?mid=" + mid + "&id=" + id);
    }
    if (id == "0")
    {
        SetSESSION("sql", MIS.GetInsertSql(dtable, flds, types));
    }
    else
    {
        SetSESSION("sql", MIS.GetUpdateSql(dtable, filter,flds, types));       
    }
    //Response.Write(GetSESSION("sql"));
    Response.Redirect("execommand.aspx?aid="+StringUtility.StringToBase64(aid)+"&mid="+mid+"&tid="+tid+"&id="+id+"&pid="+pid+"&fid="+fid);
}
private void Click_Clear(object sender, System.EventArgs e)
{   
    if(flds!=null){				
		MIS.ClearData(flds);
	}	
}
private void Click_NewAdd(object sender, System.EventArgs e)
{   
    Response.Redirect("getpage.aspx?aid="+StringUtility.StringToBase64("add")+"&mid="+mid+"&tid="+tid+"&pid="+pid);
}
private void Click_BackList(object sender, System.EventArgs e)
{   
    Response.Redirect("getpage.aspx?aid="+StringUtility.StringToBase64("list")+"&mid="+mid+"&tid="+tid+"&pid="+pid+"&navindex="+navindex);
}
//��֤�����ֶ�����
private void CheckMustAndFieldType(string mid)
{
    CheckMustAndFieldType(mid, flds);
}
    
private void CheckMustAndFieldType(string mid,string[] arrflds)
{
    string[] h_flds = arrflds;
    if (h_flds != null && mid != "0")
    {
        StringBuilder astr = new StringBuilder();
        DataTable fddt = GetFields(mid);
        DataRow[] fdrs;
        DataRow fdr;
        string h_vreturn = "";
        foreach (string cf in h_flds)
        {
            fdrs = fddt.Select(" fieldname='" + cf + "'");
            if (fdrs.Length > 0)
            {
                fdr = fdrs[0];
                h_vreturn = MIS.GetControlValue(FindControl(cf));
                if (Convert.ToBoolean(fdr["OnEditMust"]))//������
                {
                    if (h_vreturn == "")
                    {
                        astr.Append(fdr["caption"].ToString() + "����Ϊ�գ�");
                    }
                }
                if (h_vreturn.Length > 0)
                {
                    switch (fdr["fieldtypecode"].ToString())
                    {
                        case "1"://�������͵���֤����
                        case "4":
                        case "5":
                            int h_intdatalength = 0;
                            try
                            {
                                h_intdatalength = Convert.ToInt32(fdr["DataLength"].ToString());
                            }
                            catch { }
                            if (RealStringLength(h_vreturn) > h_intdatalength)
                            {
                                astr.Append(fdr["caption"].ToString() + "�ַ������ܳ���" + h_intdatalength.ToString() + "��");
                            }

                            break;
                        case "2"://����
                        case "11"://С����
                            try
                            {
                                Convert.ToInt32(h_vreturn);
                            }
                            catch
                            {
                                astr.Append(fdr["caption"].ToString() + "Ӧ����������");
                            }
                            break;
                        case "10"://������                
                            try
                            {
                                Convert.ToDouble(h_vreturn);
                            }
                            catch
                            {
                                astr.Append(fdr["caption"].ToString() + "Ӧ�������֣�");
                            }
                            break;
                        case "7"://����                
                            try
                            {
                                Convert.ToDateTime(h_vreturn);
                            }
                            catch
                            {
                                astr.Append(fdr["caption"].ToString() + "Ӧ�������ڸ�ʽ��");
                            }
                            break;
                    }
                }
            }
        }
        if (astr.Length > 0)
        {
            SetSESSION("sql", "");
            SetSESSION("alert", astr.ToString());
        }
    }
}
    
private void SetToolBar()
{
	mid = GetQueryString("mid", "0");
    tid = GetQueryString("tid", "0");
    aid = GetQueryString("aid", "list");
    id = GetQueryString("id", "0");
    navindex = GetQueryString("navindex", "0");
    //�жϸ��û��Ƿ��и�ģ��Ȩ��
    if ((";" + GetSESSION("mright") + ";").IndexOf(";" + mid + ";") == -1)
    {
        Response.Redirect("getpage.aspx?mid=0");
    }
    
    mua=";"+GetModuleActions(mid).Trim(';')+";";//ģ�����Ȩ��    
    CheckToolBar(mua.Trim(';').Split(';'));
    if(aid == "list"){				
		tb_backlist.Visible = false;
		tb_save.Visible = false;				
	}else if(aid == "view"){
		tb_2.Visible = false;
		tb_backlist.Visible=true;		
	}else if(aid == "edit"){
		tb_2.Visible = false;
		tb_save.Visible = true;
		tb_clear.Visible = true;
		tb_backlist.Visible=true;
	}else if(id == "0" && flds!=null){				
		MIS.ClearData(flds);
	}	
	SetSESSION("MainUrl",Request.Url.ToString());	  
}

</script>
<td width="100%" ><span id="alertmess" runat="server" style="color:Red;padding-left:3px;">&nbsp;</span></td>
<td id="tb_2" visible="false" runat="server" style="padding-left:5px;width:55px;" nowrap>
<X:Button id="btn_newadd" type="toolbar" mode="on" icon="tb01" text="����" onclick="Click_NewAdd" runat="server"></X:Button>
</td>
<td id="tb_save" visible="false" runat="server" style="padding-left:5px;width:55px;" nowrap>
<X:Button id="btn_save" type="toolbar" mode="on" icon="tb05" text="����" onclick="Click_Save" runat="server"></X:Button>
</td>
<td id="tb_clear" visible="false" runat="server" style="padding-left:5px;width:55px;" nowrap>
<X:Button id="btn_clear" type="toolbar" mode="on" icon="tb06" text="���" onclick="Click_Clear" runat="server"></X:Button>
</td>
<td id="tb_backlist" visible="false" runat="server" style="padding-left:5px;width:75px;" nowrap>
<X:Button id="btn_backlist" type="toolbar" mode="on" icon="tb13" text="�����б�" onclick="Click_BackList" runat="server"></X:Button>
</td>
