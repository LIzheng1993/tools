package com.test;

import java.util.Base64;

public class Encrypt {

	public static void main(String[] args) {		
		try {
			String key = "SERCERT_KEY";
			String source = "{\"companyID\":\"J0clwX8pHDcFq4pzG0OfaPdFBz7MixP5-1\",\"classname\":\"中石油1班\",\"username\":\"Amy_wa\",\"name\":\"李莉\",\"gender\":1,\"phone\":\"13856565656\",\"email\":\"Amy_wa@email.com\"}";

			byte[] keyBytes = key.getBytes("UTF-8");
			byte[] srcBytes = source.getBytes("UTF-8");
			
			int keyLength = keyBytes.length;
			int srcLength = srcBytes.length;
			System.out.println("keyLength=" + keyLength + " srcLength=" + srcLength);
			
			//加密
			for (int i = 0; i < srcLength; i ++) {
				srcBytes[i] = (byte)(srcBytes[i] ^ keyBytes[i % keyLength]);
			}
			
			StringBuilder buf = new StringBuilder();
			for (int i = 0; i < srcLength; i ++) {
				String byteStr = Integer.toHexString(srcBytes[i] & 0xff);
				if (byteStr.length() == 1) {
					buf.append('0').append(byteStr);
				}
				else {
					buf.append(byteStr);
				}
			}
			System.out.println("buf=" + buf);
			
			String dest = Base64.getEncoder().encodeToString(buf.toString().getBytes());
			System.out.println("dest=" + dest);

		}
		catch(Exception ex) {
			ex.printStackTrace();
		}
	}
}
