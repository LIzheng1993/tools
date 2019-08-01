package com.test;

import java.util.Base64;

public class Decrypt {

	public static void main(String[] args) {
		try {
			String key = "SERCERT_KEY";
			String dest = "M2U0YzE3MzAzZTIzMmUzMTMxMjQyOTY3NTQ1NjE1NjMzMDIzMjgxMDU1MWQwZDJhMTcxOTIyNjczZjI1MGY1ZDIyMjMwZjI0M2IxNTExMzU2ODA1MDQxNTE1NWI1OTZlNzE3ZjZkM2MyNDBjMWUzNjAwMTUzMjM2NzE3NTdkYWNkNWMwYTJmMWM3YjllMWVhN2ViOGM3YzA0ZjY5NGMwMTJjMzYyMTIxM2UyNTA4NGY3ZjRjMzUzMjJhMGMzODNlNmE0MTRmMmIwZjE5M2E3MTY5NmRiOWQ1ZTM4NWNiZTc1NjczNzEzNDJhMzEyYzA4MWY2NzU0NDU3MzcxMjMyNzMwMjYwODRmN2Y0YzQ1NmM2YjY2Nzk2YTdlNTg1YjcwNTg1NjczNzEzNjIyM2UyMTAxNGY3ZjRjMzUzMjJhMGMzODNlMDgwODAwMjQwNzE4NzEzMDNjMjI3ZDM1";

			byte[] keyBytes = key.getBytes("UTF-8");
			int keyLength = keyBytes.length;
			
			//解密
			byte[] bufBytes = Base64.getDecoder().decode(dest);
			
			String buf = new String(bufBytes);
			System.out.println("buf=" + buf);
			
			byte[] destBytes = new byte[buf.length() / 2];
			
			for (int i = 0; i < destBytes.length; i ++) {
				String destByte = buf.substring(i * 2, i * 2 + 2);
				destBytes[i] = (byte)(Integer.parseInt(destByte, 16));
			}
			
			for (int i = 0; i < destBytes.length; i ++) {
				destBytes[i] = (byte)(destBytes[i] ^ keyBytes[i % keyLength]);
			}
			
			String destToSrc = new String(destBytes, "UTF-8");
			System.out.println("destToSrc=" + destToSrc);
		}
		catch(Exception ex) {
			ex.printStackTrace();
		}
	}
}
