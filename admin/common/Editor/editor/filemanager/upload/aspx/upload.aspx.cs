using System;
using System.IO;
using System.Collections;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Web;
using System.Web.SessionState;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.HtmlControls;
using System.Configuration;

namespace MIS.WebApplication.Controls
{
	/// <summary>
	/// upload 的摘要说明。
	/// </summary>
	public class upload : System.Web.UI.Page
	{
		private void Page_Load(object sender, System.EventArgs e)
		{
			// 在此处放置用户代码以初始化页面
			if (Request.Files.Count > 0)
			{
				try
				{
					System.Web.HttpPostedFile oFile = Request.Files.Get("NewFile") ;
					System.DateTime N = System.DateTime.Now;
					System.Random R = new Random();
					string AttachmentsName,AttachmentsExtName;
					AttachmentsName = N.Year.ToString()+N.Month.ToString()+N.Day.ToString()+N.Hour.ToString()+N.Minute.ToString()+N.Second.ToString()+R.Next();
					AttachmentsExtName = System.IO.Path.GetExtension(oFile.FileName).ToLower();
					if(AttachmentsExtName==".doc"||AttachmentsExtName==".xls"||AttachmentsExtName==".mdb"||AttachmentsExtName==".ppt"||AttachmentsExtName==".rar"||AttachmentsExtName==".zip"||AttachmentsExtName==".avi"||AttachmentsExtName==".rm"||AttachmentsExtName==".swf")
					{
						SaveFile(AttachmentsName,AttachmentsExtName,System.IO.Path.GetFileName(oFile.FileName),oFile.InputStream,oFile.ContentLength);
						Response.Write("<SCRIPT language=javascript> window.parent.OnUploadCompleted(0,'/Admin/Common/getfile.aspx?file="+AttachmentsName+"','','') ;</" + "SCRIPT>") ;
					}
					else if(AttachmentsExtName==".jpg"||AttachmentsExtName==".jpeg"||AttachmentsExtName==".png"||AttachmentsExtName==".gif"||AttachmentsExtName==".tif"||AttachmentsExtName==".bmp")
					{
						if(AttachmentsExtName != ".bmp" && AttachmentsExtName != ".tif")
						{
							SaveFile(AttachmentsName,AttachmentsExtName,System.IO.Path.GetFileName(oFile.FileName),oFile.InputStream,oFile.ContentLength);
						}
						System.Drawing.Image rawImg = System.Drawing.Image.FromStream(oFile.InputStream);
						//格式转换
						if(AttachmentsExtName == ".bmp"||AttachmentsExtName == ".tif")
						{
							MemoryStream _ms = new MemoryStream();
							rawImg.Save(_ms,System.Drawing.Imaging.ImageFormat.Jpeg);
							SaveBuffer(AttachmentsName,".jpg",System.IO.Path.GetFileName(oFile.FileName),_ms.GetBuffer());
							_ms.Close();
							/*rawImg.Save(Server.MapPath(ConfigurationSettings.AppSettings["ATT_DIRECTORY"] + AttachmentsName+".jpg"),System.Drawing.Imaging.ImageFormat.Jpeg);
							FileStream _cs = new FileStream(Server.MapPath(ConfigurationSettings.AppSettings["ATT_DIRECTORY"] + AttachmentsName+".jpg"), FileMode.Open, FileAccess.Read, FileShare.Read);
							SaveFile(AttachmentsName,".jpg",System.IO.Path.GetFileName(oFile.FileName),(Stream)_cs,(int)_cs.Length);
							_cs.Close();
							File.Delete(Server.MapPath(ConfigurationSettings.AppSettings["ATT_DIRECTORY"] + AttachmentsName+".jpg"));*/
						}
						//生成缩略图
						string[] nailrect = ConfigurationSettings.AppSettings["IMAGENAILRECT"].Split('|');
						if(nailrect.Length != 2){nailrect = new string[]{"160","160"};}	
						System.Drawing.Image nailImg;
						System.Drawing.Image.GetThumbnailImageAbort myCallback = new System.Drawing.Image.GetThumbnailImageAbort(ThumbnailCallback);
						double r = System.Math.Max((double)(rawImg.Size.Width/Convert.ToDouble(nailrect[0])),(double)(rawImg.Size.Height/Convert.ToDouble(nailrect[1])));
						if(r>1.00)
						{
							nailImg = rawImg.GetThumbnailImage(Convert.ToInt32(rawImg.Size.Width/r), Convert.ToInt32(rawImg.Size.Height/r), myCallback, IntPtr.Zero);
						}
						else
						{
							nailImg = rawImg.GetThumbnailImage(rawImg.Size.Width, rawImg.Size.Height, myCallback, IntPtr.Zero);
						}
						/*nailImg.Save(Server.MapPath(ConfigurationSettings.AppSettings["ATT_DIRECTORY"] + AttachmentsName+"_nail.jpg"),System.Drawing.Imaging.ImageFormat.Jpeg);
						FileStream _ns = new FileStream(Server.MapPath(ConfigurationSettings.AppSettings["ATT_DIRECTORY"] + AttachmentsName+"_nail.jpg"), FileMode.Open, FileAccess.Read, FileShare.Read);
						SaveFile(AttachmentsName+"_nail",".jpg",System.IO.Path.GetFileName(oFile.FileName),(Stream)_ns,(int)_ns.Length);
						_ns.Close();
						File.Delete(Server.MapPath(ConfigurationSettings.AppSettings["ATT_DIRECTORY"] + AttachmentsName+"_nail.jpg"));
						nailImg.Dispose();*/
						MemoryStream _ns = new MemoryStream();
						nailImg.Save(_ns,System.Drawing.Imaging.ImageFormat.Jpeg);
						SaveBuffer(AttachmentsName+"_nail",".jpg",System.IO.Path.GetFileName(oFile.FileName),_ns.GetBuffer());
						_ns.Close();
						nailImg.Dispose();
						rawImg.Dispose();
						Response.Write("<SCRIPT language=javascript>window.parent.OnUploadCompleted(0,'/Admin/Common/getfile.aspx?file="+AttachmentsName+"','','') ;</" + "SCRIPT>") ;
					}
					else
					{
						Response.Write("<SCRIPT language=javascript> window.parent.OnUploadCompleted(202,'"+System.IO.Path.GetFileName(oFile.FileName)+"','','') ;</" + "SCRIPT>") ;
					}
				}
				catch(Exception ex)
				{
					Response.Write("<SCRIPT language=javascript> window.parent.OnUploadCompleted(1,'','','"+ex.Message+"') ;</" + "SCRIPT>") ;//服务器未正确配置！
				}

			}
		}
		private void SaveBuffer(string fname,string ename,string desc,byte[] con)
		{
			SqlDB db = new SqlDB();
			db.SqlString = "INSERT INTO gmis_File (FileName,FileExtName,FileDesc,FileData) VALUES ('"+fname+"','"+ename+"','"+desc+"','"+System.Convert.ToBase64String(con)+"')";
			db.UpdateTable();
		}
		private void SaveFile(string fname,string ename,string desc,Stream fstream,int conlength)
		{
			byte[] con = new byte[conlength];
			fstream.Read(con,0,conlength);
			SaveBuffer(fname,ename,desc,con);
		}
		private bool ThumbnailCallback()
		{
			return false;
		}

		#region Web Form Designer generated code
		override protected void OnInit(EventArgs e)
		{
			//
			// CODEGEN：该调用是 ASP.NET Web 窗体设计器所必需的。
			//
			InitializeComponent();
			base.OnInit(e);
		}
		
		/// <summary>
		/// 设计器支持所需的方法 - 不要使用代码编辑器修改
		/// 此方法的内容。
		/// </summary>
		private void InitializeComponent()
		{    
			this.Load += new System.EventHandler(this.Page_Load);
		}
		#endregion
	}
}
