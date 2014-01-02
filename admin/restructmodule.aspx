<%@ Import Namespace="System.Data" %>
<%@ Import Namespace="System.Text" %>
<%@ Import Namespace="System.Web.UI.WebControls" %>

<script runat=server language=C#>	
    StringBuilder sb;

    private string UpdateTypeIndex(string dtable, string fmocodename, string flevelname, string findexname, string fpositionname, string fuppercodename)
    {
        sb = new StringBuilder();
        if (db.UpdateTable(db.ConnStr, "update " + dtable + " set " + findexname + "=" + fmocodename + " where " + flevelname + "=1 ") == "²Ù×÷³É¹¦£¡")
        {
            DataTable dt = db.GetDataTable(db.ConnStr, "select " + fmocodename + "," + flevelname + " as level," + fuppercodename + " as uppercode from " + dtable + "  order by " + findexname + " ");
            GetTypeIndexSQl("1.", "Level=1", dt, dtable, fmocodename, findexname, fpositionname);
        }
        return "Begin " + sb.ToString() + " End";
    }

    private void GetTypeIndexSQl(string pindex, string filter, DataTable dt, string dtable, string fmocodename, string findexname, string fpositionname)
    {
        DataRow[] drs = dt.Select(filter, "");
        for (int i = 0; i < drs.Length; i++)
        {
            string tindex = pindex + (i + 1).ToString().PadLeft(3, '0');
            sb.Append("update " + dtable + " set " + findexname + "='" + tindex + "'," + fpositionname + "=" + Convert.ToString(i + 1) + " where " + fmocodename + "='" + drs[i][fmocodename].ToString() + "';");
            if (dt.Select("UpperCode='" + drs[i][fmocodename].ToString() + "'", "").Length > 0)
            {
                GetTypeIndexSQl(tindex, "UpperCode='" + drs[i][fmocodename].ToString() + "'", dt, dtable, fmocodename, findexname, fpositionname);
            }
        }
    }
    
</script>