import Head from "next/head";
import { useEffect, useState } from "react";
import {HydraAdmin, ResourceGuesser} from "@api-platform/admin";


const Admin = () => {

  // Load the admin client-side
  const [DynamicAdmin, setDynamicAdmin] = useState(<p>Loading...</p>);
  useEffect(() => {
    (async () => {
      const HydraAdmin = (await import("@api-platform/admin")).HydraAdmin;
      const FeedbackList = (await import("./../../components/admin")).FeedbackList;
      const FeedbackCreate = (await import("./../../components/admin")).FeedbackCreate;
      const FeedbackEdit = (await import("./../../components/admin")).FeedbackEdit;
      const FeedbackShow = (await import("./../../components/admin")).FeedbackShow;
      const MenuList = (await import("./../../components/admin")).MenuList;
      const MenuCreate = (await import("./../../components/admin")).MenuCreate;
      const MenuEdit = (await import("./../../components/admin")).MenuEdit;
      const MenuShow = (await import("./../../components/admin")).MenuShow;
      const PageList = (await import("./../../components/admin")).PageList;
      const PageCreate = (await import("./../../components/admin")).PageCreate;
      const PageEdit = (await import("./../../components/admin")).PageEdit;
      const PageShow = (await import("./../../components/admin")).PageShow;
      const UserList = (await import("./../../components/admin")).UserList;
      const UserCreate = (await import("./../../components/admin")).UserCreate;
      const UserEdit = (await import("./../../components/admin")).UserEdit;
      const UserShow = (await import("./../../components/admin")).UserShow;

      setDynamicAdmin(<HydraAdmin entrypoint={window.origin}>
        <ResourceGuesser name={"feedback"} list={FeedbackList} create={FeedbackCreate} edit={FeedbackEdit} show={FeedbackShow} />
        <ResourceGuesser name={"menus"} list={MenuList} create={MenuCreate} edit={MenuEdit} show={MenuShow} />
        <ResourceGuesser name={"pages"} list={PageList} create={PageCreate} edit={PageEdit} show={PageShow} />
        <ResourceGuesser name={"users"} list={UserList} create={UserCreate} edit={UserEdit} show={UserShow} />
      </HydraAdmin>);
    })();
  }, []);

  return (
    <>
      <Head>Admin</Head>
      {DynamicAdmin}
    </>
  );
};
export default Admin;
