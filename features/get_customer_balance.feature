Feature: Get Customer Balances
    As a user
    I want to retrieve the balance for customers
    So that I can view their current financial status


    Background:
        Given There are invoices:
    | id                                   | type    | customerId                           | invoiceNumber          | nett_amount | gross_amount | tax_amount | currency | created_at          |
    | b3eccb1c-871f-4712-94e7-ff06eadbe8be | invoice | 002b372a-2e0d-454e-ae9b-5b3c8e15a9fa | DP/2018/11/03/57350901 | 2810.43     | 3456.83      | 646.4      | EUR      | 2018-11-03 03:33:00 |
    | 7ec1d8d5-741f-44cf-8306-9b7668030c57 | invoice | 002b372a-2e0d-454e-ae9b-5b3c8e15a9fa | DP/2022/08/08/47114764 | 1695.17     | 2085.06      | 389.89     | EUR      | 2022-08-08 04:48:00 |
    | 0aad8446-29af-4cb6-b24c-cc9e2aab3fdf | invoice | 002b372a-2e0d-454e-ae9b-5b3c8e15a9fa | DP/2021/11/03/91269065 | 890.09      | 1094.81      | 204.72     | PLN      | 2021-11-03 23:14:00 |
    | bb9163d4-8ded-4077-bd07-77f8506c76d3 | invoice | fedbcfc1-2b87-4a57-99c7-ad02b120d057 | DP/2004/07/04/94493095 | 3102.26     | 3815.78      | 713.52     | EUR      | 2004-07-04 23:59:00 |
    | 382a23b7-6a1a-4871-9da7-b8f1d22cc1a2 | invoice | fea1b900-cf3f-4112-95c3-d0019e626712 | DP/2000/03/04/44863393 | 822.97      | 1012.25      | 189.28     | EUR      | 2000-03-04 07:23:00 |
    | 3fb60ffc-2f3c-4c4f-8e87-62fbaa75afd7 | draft   | ce962250-2b34-4b87-a4a6-9a68df5c957d | DP/2021/02/02/78233803 | 1927.77     | 2371.16      | 443.39     | PLN      | 2021-02-02 17:53:00 |
    | 9d38959c-0206-4050-9bf8-73ab35e56919 | draft   | 8d741657-5322-4f44-860a-9450bc00f4d8 | DP/2022/12/08/52216766 | 2168.32     | 2667.03      | 498.71     | PLN      | 2022-12-08 02:12:00 |


        And There are payments:
    | id                                   | currency | amount  | customer_id                          |
    | 323041a7-0d88-4649-9293-a63faa3b11b0 | EUR      | 3456.83 | 002b372a-2e0d-454e-ae9b-5b3c8e15a9fa |
    | 6110b15f-29f2-4519-9221-826b210cfede | EUR      | 2085.06 | 002b372a-2e0d-454e-ae9b-5b3c8e15a9fa |
    | 819325e5-6fe8-4c44-832b-ee614b98d098 | EUR      | 3815.78 | fedbcfc1-2b87-4a57-99c7-ad02b120d057 |
    | 08285389-bca2-4b44-8b8a-7a762585abc1 | EUR      | 1012.25 | fea1b900-cf3f-4112-95c3-d0019e626712 |
    | 4e6fff79-4b2c-4088-89b2-68907a777b37 | PLN      | 2371.16 | ce962250-2b34-4b87-a4a6-9a68df5c957d |
    | ec1b3e20-cbe0-4914-8134-277011d08d09 | PLN      | 2667.03 | 8d741657-5322-4f44-860a-9450bc00f4d8 |

    Scenario: Get customers balance successfully
        When I request for the balance
        Then the system should respond with status code 200
        And the response for user should look like this "raport"

    Scenario: Get customer balance successfully
        When I request for the balance for the customerId "002b372a-2e0d-454e-ae9b-5b3c8e15a9fa"
        Then the system should respond with status code 200
        And the response for user should look like this "002b372a-2e0d-454e-ae9b-5b3c8e15a9fa"

    Scenario: Get balance for not existing customer
        When I request for the balance for the customerId "8d741657-5322-4f44-860a-9450bc00f4d9"
        Then the system should respond with status code 400
