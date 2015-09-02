package com.hogesoft.wowza.output;

import java.util.*;

import com.wowza.wms.dvr.*;
import com.wowza.wms.dvr.IDvrConstants.DvrTimeScale;
import com.wowza.wms.httpstreamer.model.*;

public class DvrStartDurationPlaylistRequestDelegate extends com.wowza.wms.dvr.impl.DvrStartDurationPlaylistRequestDelegate {

//	@Override
//	public DvrPlaylistRequest getDvrPlaylistRequest(IHTTPStreamerApplicationContext arg0, IDvrStreamStore arg1, Map<String, String> arg2) {
//		DvrPlaylistRequest request = super.getDvrPlaylistRequest(arg0, arg1, arg2);
//		request.setTimeScale(DvrTimeScale.DVR_TIME);
//		return request;
//	}
//
//	@Override
//	public DvrPlaylistRequest getDvrPlaylistRequest(IHTTPStreamerApplicationContext arg0, List<IDvrStreamStore> arg1, Map<String, String> arg2) {
//		DvrPlaylistRequest request = super.getDvrPlaylistRequest(arg0, arg1, arg2);
//		request.setTimeScale(DvrTimeScale.DVR_TIME);
//		return request;
//	}
	public DvrPlaylistRequest getDvrPlaylistRequest(IHTTPStreamerApplicationContext appContext, IDvrStreamStore store, Map<String, String> queryMap) {
		DvrPlaylistRequest availablePlaylist = getDefaultPlaylistRequest(DvrTimeScale.DVR_TIME, store);

		DvrPlaylistRequest newRequest = createRequestFromQueryParams(appContext, queryMap, availablePlaylist);

		return newRequest;
	}

	public DvrPlaylistRequest getDvrPlaylistRequest(IHTTPStreamerApplicationContext appContext, List<IDvrStreamStore> stores, Map<String, String> queryMap) {
		DvrPlaylistRequest availablePlaylist = getDefaultPlaylistRequest(DvrTimeScale.DVR_TIME, stores);

		DvrPlaylistRequest newRequest = createRequestFromQueryParams(appContext, queryMap, availablePlaylist);

		return newRequest;
	}

	private DvrPlaylistRequest createRequestFromQueryParams(IHTTPStreamerApplicationContext appContext, Map<String, String> queryMap, DvrPlaylistRequest availablePlaylist) {

		DvrPlaylistRequest newRequest = new DvrPlaylistRequest(DvrTimeScale.UTC_TIME);
		if (availablePlaylist != null) {
			newRequest.setPlaylistEnd(availablePlaylist.getPlaylistEnd());
			newRequest.setPlaylistStart(availablePlaylist.getPlaylistStart());
		}
		
		String playStartQueryParameter = "starttime";
		String playDurationQueryParameter = "duration";
		long now = System.currentTimeMillis() - 360000;

		String playStartStr = queryMap.get(playStartQueryParameter);

		if (playStartStr != null) {
			try {
				long playStart = Long.parseLong(playStartStr);
				if (playStart < availablePlaylist.getPlaylistStart()) {
					playStart = availablePlaylist.getPlaylistStart();
				} 
				if (playStart > now) {
					
					playStart = now;
				}
//				System.out.printf("==========playlist start:%d now:%s\n.", playStart, now);
				newRequest.setPlaylistStart(playStart);
			} catch (Exception e) {
			}
		}

		String playDurationStr = queryMap.get(playDurationQueryParameter);
		if (playDurationStr != null) {
			try {
				long playDuration = Long.parseLong(playDurationStr);
				long playEnd = newRequest.getPlaylistStart() + playDuration;
				if (playEnd < availablePlaylist.getPlaylistStart() || playEnd > now) {
					playEnd = now;
				}
//				System.out.printf("==========playlist start:%d end:%s\n.", playEnd, now);
				newRequest.setPlaylistEnd(playEnd);
			} catch (Exception e) {
			}
		}
		return newRequest;
	}
}

