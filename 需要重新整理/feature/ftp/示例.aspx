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
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using System.Net;

namespace PCT.FCL
{
    public class FTPHelper : IDisposable
    {
        public FTPHelper(string requestUriString, string userName, string password)
        {
            this.requestUriString = requestUriString;
            this.userName = userName;
            this.password = password;
        }

        private string requestUriString;
        private string userName;
        private string password;
        private FtpWebRequest request;
        private FtpWebResponse response;

        /// <summary>
        /// 创建FtpWebRequest
        /// </summary>
        /// <param name="uriString">FTP路径</param>
        /// <param name="method">FTP方法</param>
        private void OpenRequest(string uriString, string method)
        {
            this.request = WebRequest.Create(new Uri(this.requestUriString + uriString)) as FtpWebRequest;
            this.request.Credentials = new NetworkCredential(userName, password);
            this.request.UseBinary = true;
            this.request.Method = method;
        }

        /// <summary>
        /// 返回FtpWebResponse
        /// </summary>
        /// <param name="uriString">FTP路径</param>
        /// <param name="method">FTP方法</param>
        private void OpenResponse(string uriString, string method)
        {
            this.OpenRequest(uriString, method);
            this.response = this.request.GetResponse() as FtpWebResponse;
        }

        /// <summary>
        /// 创建目录
        /// </summary>
        /// <param name="directoryPath">要创建的目录名称</param>
        public void MakeDirectory(string directoryPath)
        {
            this.OpenResponse(directoryPath, WebRequestMethods.Ftp.MakeDirectory);
        }

        /// <summary>
        /// 删除目录
        /// </summary>
        /// <param name="directoryPath">要删除的目录名称</param>
        public void RemoveDirectory(string directoryPath)
        {
            this.OpenResponse(directoryPath, WebRequestMethods.Ftp.RemoveDirectory);
        }

        /// <summary>
        /// 重命名文件
        /// </summary>
        /// <param name="originalFileName">原文件名</param>
        /// <param name="newFileName">新文件名</param>
        public void Rename(string originalFileName, string newFileName)
        {
            this.OpenResponse(originalFileName, WebRequestMethods.Ftp.Rename);
            this.request.RenameTo = newFileName;
            this.response = this.request.GetResponse() as FtpWebResponse;
        }

        /// <summary>
        /// 上传文件到服务器
        /// </summary>
        /// <param name="localFullPath">要上传的本地文件全路径</param>
        public void UploadFile(string localFullPath)
        {
            this.UploadFile(localFullPath, false);
        }

        /// <summary>
        /// 上传文件到服务器
        /// </summary>
        /// <param name="localFullPath">要上传的本地文件全路径</param>
        /// <param name="overWriteFile">是否要重写服务器上的文件</param>
        public void UploadFile(string localFullPath, bool overWriteFile)
        {
            this.UploadFile(localFullPath, Path.GetFileName(localFullPath), overWriteFile);
        }

        /// <summary>
        /// 上传文件到服务器
        /// </summary>
        /// <param name="localFullPath">要上传的本地文件全路径</param>
        /// <param name="remoteFileName">上传后文件重命名为</param>
        public void UploadFile(string localFullPath, string remoteFileName)
        {
            this.UploadFile(localFullPath, remoteFileName, false);
        }

        /// <summary>
        /// 上传文件到服务器
        /// </summary>
        /// <param name="localFullPath">要上传的本地文件全路径</param>
        /// <param name="remoteFileName">上传后文件重命名为</param>
        /// <param name="overWriteFile">是否要重写服务器上的文件</param>
        public void UploadFile(string localFullPath, string remoteFileName, bool overWriteFile)
        {
            byte[] fileBytes = null;
            using (FileStream fileStream = new FileStream(localFullPath, FileMode.Open, FileAccess.Read))
            {
                fileBytes = new byte[fileStream.Length];
                fileStream.Read(fileBytes, 0, (Int32)fileStream.Length);
            }
            this.UploadFile(fileBytes, remoteFileName, overWriteFile);
        }

        /// <summary>
        /// 上传文件到服务器
        /// </summary>
        /// <param name="fileBytes">上传文件的字节流</param>
        /// <param name="remoteFileName">上传后文件重命名为</param>
        public void UploadFile(byte[] fileBytes, string remoteFileName)
        {
            this.UploadFile(fileBytes, remoteFileName, false);
        }

        /// <summary>
        /// 上传文件到服务器
        /// </summary>
        /// <param name="fileBytes">上传文件的字节流</param>
        /// <param name="remoteFileName">上传后文件重命名为</param>
        /// <param name="overWriteFile">是否要重写服务器上的文件</param>
        public void UploadFile(byte[] fileBytes, string remoteFileName, bool overWriteFile)
        {
            this.OpenResponse(overWriteFile ? remoteFileName : Gadget.ReturnFileNameWithCurrentDate(remoteFileName), WebRequestMethods.Ftp.UploadFile);
            using (Stream stream = this.request.GetRequestStream())
            {
                using (MemoryStream memoryStream = new MemoryStream(fileBytes))
                {
                    byte[] buffer = new byte[Constant.FTP.LenOfBuffer];
                    int bytesRead = 0;
                    int totalRead = 0;
                    while (true)
                    {
                        bytesRead = memoryStream.Read(buffer, 0, buffer.Length);
                        if (bytesRead == 0)
                        {
                            break;
                        }
                        totalRead += bytesRead;
                        stream.Write(buffer, 0, bytesRead);
                    }
                }
                this.response = this.request.GetResponse() as FtpWebResponse;
            }
        }

        /// <summary>
        /// 下载服务器文件到本地
        /// </summary>
        /// <param name="remoteFileName">要下载的服务器文件名</param>
        /// <param name="localPath">下载到本地的路径</param>
        public void DownloadFile(string remoteFileName, string localPath)
        {
            this.DownloadFile(remoteFileName, localPath, false);
        }

        /// <summary>
        /// 下载服务器文件到本地
        /// </summary>
        /// <param name="remoteFileName">要下载的服务器文件名</param>
        /// <param name="localPath">下载到本地的路径</param>
        /// <param name="overWriteFile">是否要重写本地的文件</param>
        public void DownloadFile(string remoteFileName, string localPath, bool overWriteFile)
        {
            this.DownloadFile(remoteFileName, localPath, remoteFileName, overWriteFile);
        }

        /// <summary>
        /// 下载服务器文件到本地
        /// </summary>
        /// <param name="remoteFileName">要下载的服务器文件名</param>
        /// <param name="localPath">下载到本地的路径</param>
        /// <param name="localFileName">下载到本地的文件重命名为</param>
        public void DownloadFile(string remoteFileName, string localPath, string localFileName)
        {
            this.DownloadFile(remoteFileName, localPath, localFileName, false);
        }

        /// <summary>
        /// 下载服务器文件到本地
        /// </summary>
        /// <param name="remoteFileName">要下载的服务器文件名</param>
        /// <param name="localPath">下载到本地的路径</param>
        /// <param name="localFileName">下载到本地的文件重命名为</param>
        /// <param name="overWriteFile">是否要重写本地的文件</param>
        public void DownloadFile(string remoteFileName, string localPath, string localFileName, bool overWriteFile)
        {
            byte[] fileBytes = this.DownloadFile(remoteFileName);
            if (fileBytes != null)
            {
                using (FileStream fileStream = new FileStream(Path.Combine(localPath, overWriteFile ? localFileName : Gadget.ReturnFileNameWithCurrentDate(localFileName)), FileMode.Create))
                {
                    fileStream.Write(fileBytes, 0, fileBytes.Length);
                    fileStream.Flush();
                }
            }
        }

        /// <summary>
        /// 下载服务器文件到本地
        /// </summary>
        /// <param name="RemoteFileName">要下载的服务器文件名</param>
        /// <returns>文件的字节流</returns>
        public byte[] DownloadFile(string RemoteFileName)
        {
            this.OpenResponse(RemoteFileName, WebRequestMethods.Ftp.DownloadFile);
            using (Stream stream = this.response.GetResponseStream())
            {
                using (MemoryStream memoryStream = new MemoryStream(Constant.FTP.CapacityofMemeoryStream))
                {
                    byte[] buffer = new byte[Constant.FTP.LenOfBuffer];
                    int bytesRead = 0;
                    int TotalRead = 0;
                    while (true)
                    {
                        bytesRead = stream.Read(buffer, 0, buffer.Length);
                        TotalRead += bytesRead;
                        if (bytesRead == 0)
                            break;
                        memoryStream.Write(buffer, 0, bytesRead);
                    }
                    return memoryStream.Length > 0 ? memoryStream.ToArray() : null;
                }
            }
        }

        /// <summary>
        /// 删除服务器文件
        /// </summary>
        /// <param name="directoryPath">要删除的文件路径</param>
        /// <param name="fileName">要删除的文件名</param>
        public void DeleteFile(string directoryPath, string fileName)
        {
            this.DeleteFile(directoryPath + fileName);
        }

        /// <summary>
        /// 删除服务器文件
        /// </summary>
        /// <param name="fileName">要删除的文件名</param>
        public void DeleteFile(string fileName)
        {
            this.OpenResponse(fileName, WebRequestMethods.Ftp.DeleteFile);
        }

        /// <summary>
        /// 列出服务器上指定目录下的所有文件
        /// </summary>
        /// <param name="directoryPath">指定的目录</param>
        /// <returns>文件名列表</returns>
        public string[] LisFiles(string directoryPath)
        {
            List<string> filesList = new List<string>();
            this.OpenResponse(directoryPath, WebRequestMethods.Ftp.ListDirectory);
            using (StreamReader streamReader = new StreamReader(this.response.GetResponseStream()))
            {
                return Gadget.SplitString(streamReader.ReadToEnd(), Constant.TextConstant.FtpNewLine);
            }
        }

        /// <summary>
        /// 列出服务器上指定目录下的所有子目录
        /// </summary>
        /// <param name="directoryPath">指定的目录</param>
        /// <returns>子目录名列表</returns>
        public string[] ListDirectories(string directoryPath)
        {
            List<string> directoriesList = new List<string>();
            this.OpenResponse(directoryPath, WebRequestMethods.Ftp.ListDirectoryDetails);
            using (StreamReader streamReader = new StreamReader(this.response.GetResponseStream()))
            {
                string line = streamReader.ReadLine();
                if (line != null)
                {
                    while (line != null)
                    {
                        //line = drwxrwxrwx   1 user     group           0 Jul 15 18:17 Archive_20110714
                        line = line.Substring(line.LastIndexOf(Constant.TextConstant.Colon) + Constant.FTP.LenToDirectory);
                        if (!line.StartsWith(Constant.FileConstant.FileTypeSeperator))//remove the folder like . or ..
                        {
                            directoriesList.Add(line);
                        }
                        line = streamReader.ReadLine();
                    }
                    return directoriesList.ToArray<string>();
                }
            }
            return null;
        }

        /// <summary>
        /// 检查指定的目录是否在服务器上存在
        /// </summary>
        /// <param name="directory">指定的目录</param>
        /// <returns>如果存在返回true，否则返回false</returns>
        public bool DirectoryExist(string directory)
        {
            try
            {
                this.OpenResponse(directory, WebRequestMethods.Ftp.GetDateTimestamp);
                return true;
            }
            catch (Exception)
            {
                return false;
            }
        }

        /// <summary>
        /// 检查指定的文件是否在服务器上存在
        /// </summary>
        /// <param name="directoryPath">指定的目录</param>
        /// <param name="remoteFileName">指定的文件</param>
        /// <returns>如果存在返回true，否则返回false</returns>
        public bool FileExist(string directoryPath, string remoteFileName)
        {
            string[] fileNames = this.LisFiles(directoryPath);
            foreach (string fileName in fileNames)
            {
                if (string.Compare(fileName, remoteFileName, true) == 0)
                {
                    return true;
                }
            }
            return false;
        }

        /// <summary>
        /// 释放资源
        /// </summary>
        public void Dispose()
        {
            this.Close();
        }

        /// <summary>
        /// 释放资源
        /// </summary>
        public void Close()
        {
            if (this.response != null)
            {
                this.response.Close();
                this.response = null;
            }
        }
    }
}
