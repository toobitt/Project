package com.hogesoft.wowza.utils;

import java.io.File;
import java.io.FileOutputStream;
import java.io.InputStream;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;

public class HttpUtils {

	public interface OnCompleteListener {
		void completeHandler(String response);
	}

	public interface OnErrorListener {
		void errorHandler(int statusCode);
	}

	private OnCompleteListener _onCompleteListener = null;

	private OnErrorListener _onErrorListener = null;

	public void setOnCompleteListener(OnCompleteListener _onCompleteListener) {
		this._onCompleteListener = _onCompleteListener;
	}

	public void setOnErrorListener(OnErrorListener _onErrorListener) {
		this._onErrorListener = _onErrorListener;
	}

	public void upload(File file, String url) throws Exception {
		HttpClient httpclient = new DefaultHttpClient();
		HttpPost httppost = new HttpPost(url);
		FileBody bin = new FileBody(file);
		MultipartEntity reqEntity = new MultipartEntity();
		reqEntity.addPart("videofile", bin);
		httppost.setEntity(reqEntity);
		HttpResponse response = httpclient.execute(httppost);
		int statusCode = response.getStatusLine().getStatusCode();
		if (statusCode == HttpStatus.SC_OK) {
			HttpEntity resEntity = response.getEntity();
			if (_onCompleteListener != null)
				_onCompleteListener.completeHandler(EntityUtils.toString(resEntity));
			EntityUtils.consume(resEntity);
		} else {
			if (_onErrorListener != null)
				_onErrorListener.errorHandler(statusCode);
		}
		httppost.releaseConnection();
		httpclient.getConnectionManager().shutdown();
	}

	public void download(File file, String url) throws Exception {
		HttpClient httpclient = new DefaultHttpClient();
		HttpGet httpGet = new HttpGet(url);
        HttpResponse response = httpclient.execute(httpGet);
		int statusCode = response.getStatusLine().getStatusCode();
		if (statusCode == HttpStatus.SC_OK) {
	        InputStream in = response.getEntity().getContent();
	        FileOutputStream out = new FileOutputStream(file);
	        byte[] b = new byte[2048];
	        int len = 0;
	        while ((len = in.read(b)) != -1) {
	            out.write(b, 0, len);
	            out.flush();
	        }
	        in.close();
	        out.close();
			if (_onCompleteListener != null)
				_onCompleteListener.completeHandler("");
		} else {
			if (_onErrorListener != null)
				_onErrorListener.errorHandler(statusCode);
		}
        httpGet.releaseConnection();
		httpclient.getConnectionManager().shutdown();
	}

	public void sendToURL(String url) throws Exception {
		HttpClient httpclient = new DefaultHttpClient();
		HttpGet httpGet = new HttpGet(url);
        httpclient.execute(httpGet);
        httpGet.releaseConnection();
		httpclient.getConnectionManager().shutdown();
	}
}
