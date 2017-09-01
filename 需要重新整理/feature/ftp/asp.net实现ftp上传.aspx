<%@ Page Language="C#" Trace="true" %>
<!DOCTYPE html>
<html>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<head>
		<title>ASPX Page</title>
	</head>
	<body>
	 	
	</body>
</html>

在asp.net中实现文件上传
<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="webftp._Default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <title></title>
    
</head>
<body>
    <form id="form1" runat="server" action="Default.aspx">
    
    <input id="File1" type="file" runat="server" />
    <input id="Submit1" type="submit" value="submit" />
    
    </form>
</body>
</html>


///对应的.cs文件中
using System;
using System.IO;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace webftp
{
    public partial class _Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (Page.IsPostBack)
            {
                System.Web.HttpFileCollection files = Request.Files;
                if (files == null || files.Count == 0)
                {
                    Response.Write("没有选择文件");
                }
                else
                {
                    for (int fileCount = 0; fileCount < files.Count; fileCount++)
                    {
                        System.Web.HttpPostedFile postedFile = files[fileCount];

                        string fileName =System.IO.Path.GetFileName(postedFile.FileName);
                        Response.Write("上传文件"+fileName);
                        string directory = Server.MapPath("/UPloadFiles/");
                        Response.Write("<br/>"+directory);
                        string path = directory + fileName;
                        Response.Write("<br/>" + path);
                        if (!Directory.Exists(directory))
                        {
                            Directory.CreateDirectory(directory);
                        }
                        if (File.Exists(path))
                        {
                            File.Delete(path);
                        }
                        postedFile.SaveAs(path);

                    }
                }
            }
        }
    }
}




对ftp服务器上传文件
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Net;
using System.IO; 

namespace WebApplication1
{
    public partial class _Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            //调用例子
            FtpStatusCode status=UploadFile(@"C:\Users\admin\Desktop\ftp上传.txt","ftp://127.0.0.1:592/");
        }

        //自己写的ftp上传文件的函数
        public FtpStatusCode UploadFile(string path, string url)
        {
            FtpWebResponse uploadResponse=null;
            Stream requestStream = null;
            FileStream fileStream = null;
            try
            {
                string fileName = System.IO.Path.GetFileName(path);
                FtpWebRequest uploadRequest = (FtpWebRequest)WebRequest.Create(url + fileName);
                uploadRequest.Method = WebRequestMethods.Ftp.UploadFile;
                uploadRequest.Proxy = null;
                uploadRequest.Credentials = new NetworkCredential("ftp", "123456");
                uploadRequest.UseBinary = true;

                requestStream = uploadRequest.GetRequestStream();
                fileStream = File.Open(path, FileMode.Open);

                byte[] buffer = new byte[1024];
                int bytesRead;
                while (true)
                {
                    bytesRead = fileStream.Read(buffer, 0, buffer.Length);
                    if (bytesRead == 0)
                        break;
                    requestStream.Write(buffer, 0, bytesRead);
                }
                requestStream.Close();

                uploadResponse = (FtpWebResponse)uploadRequest.GetResponse();
                return uploadResponse.StatusCode;
            }
            catch (Exception exce)
            {
                Response.Write(exce);
            }
            finally
            {
                if (uploadResponse != null)
                    uploadResponse.Close();
                if (fileStream != null)
                    fileStream.Close();
                if (requestStream != null)
                    requestStream.Close();
            }
            return FtpStatusCode.Undefined;
        }
    }
}
