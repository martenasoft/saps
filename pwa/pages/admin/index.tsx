import Head from "next/head";
import { useEffect, useState } from "react";
import {FieldGuesser, HydraAdmin, ListGuesser} from "@api-platform/admin";

const Admin = () => {

  // // Load the admin client-side
  // const [DynamicAdmin, setDynamicAdmin] = useState(<p>Loading...</p>);
  // useEffect(() => {
  //   (async () => {
  //     const HydraAdmin = (await import("@api-platform/admin")).HydraAdmin;
  //
  //     setDynamicAdmin(<HydraAdmin entrypoint={window.origin}>{children}</HydraAdmin>);
  //   })();
  // }, []);

  return (<HydraAdmin entrypoint="https://localhost"></HydraAdmin>
  );
};
export default Admin;
